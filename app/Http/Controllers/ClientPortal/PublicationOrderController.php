<?php

namespace App\Http\Controllers\ClientPortal;

use App\Http\Controllers\Controller;
use App\Models\PublicationOrder;
use App\Models\PublicationOrderDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicationOrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $orders = PublicationOrder::with('publication')
            ->when($user->client_id, fn ($q) => $q->where('client_id', $user->client_id))
            ->when(! $user->client_id, fn ($q) => $q->where('email', $user->email))
            ->latest()
            ->get();
        return view('portal.publication-orders.index', compact('orders'));
    }

    public function show(PublicationOrder $order)
    {
        $this->authorizeOrder($order);
        $order->load(['publication', 'documents']);
        return view('portal.publication-orders.show', ['order' => $order, 'guest' => false]);
    }

    public function submit(Request $request, PublicationOrder $order)
    {
        $this->authorizeOrder($order);
        if (! $order->canSubmit()) {
            return redirect()->back()->with('error', 'This order has already been submitted.');
        }

        $valid = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:50000',
            'featured_image' => 'nullable|image|max:5120',
            'documents.*' => 'nullable|file|max:10240',
        ]);

        $order->update([
            'title' => $valid['title'],
            'content' => $valid['content'],
            'status' => 'submitted',
        ]);

        if ($request->hasFile('featured_image')) {
            if ($order->featured_image_path) {
                Storage::disk('public')->delete($order->featured_image_path);
            }
            $order->update(['featured_image_path' => $request->file('featured_image')->store('publication-orders', 'public')]);
        }

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                if (! $file->isValid()) continue;
                $path = $file->store('order-' . $order->id, 'publication_order_documents');
                PublicationOrderDocument::create([
                    'publication_order_id' => $order->id,
                    'name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        $redirect = $order->client_id
            ? route('portal.publication-orders.show', $order)
            : route('publication-orders.show', $order->access_token);

        return redirect($redirect)->with('success', 'Your content has been submitted. We will be in touch shortly.');
    }

    public function downloadDocument(PublicationOrder $order, PublicationOrderDocument $document)
    {
        $this->authorizeOrder($order);
        if ($document->publication_order_id !== $order->id) abort(404);
        return Storage::disk('publication_order_documents')->download($document->file_path, $document->name);
    }

    private function authorizeOrder(PublicationOrder $order): void
    {
        $user = auth()->user();
        if (! $user) abort(403);
        if ($order->client_id) {
            if ($order->client_id !== $user->client_id) abort(403);
        } else {
            if (strcasecmp($order->email, $user->email) !== 0) abort(403);
        }
    }
}
