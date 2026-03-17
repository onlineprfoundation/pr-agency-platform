<?php

namespace App\Http\Controllers;

use App\Models\PackageOrder;
use App\Models\PackageOrderDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PackageOrderController extends Controller
{
    public function showByToken(string $token)
    {
        $order = PackageOrder::where('access_token', $token)->with(['package', 'documents'])->firstOrFail();
        return view('orders.show', ['order' => $order, 'guest' => true]);
    }

    public function submitByToken(Request $request, string $token)
    {
        $order = PackageOrder::where('access_token', $token)->firstOrFail();
        if (! $order->canSubmit()) {
            return redirect()->route('orders.show', $token)->with('error', 'This order has already been submitted.');
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
            $order->update(['featured_image_path' => $request->file('featured_image')->store('package-orders', 'public')]);
        }

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                if (! $file->isValid()) continue;
                $path = $file->store('order-' . $order->id, 'package_order_documents');
                PackageOrderDocument::create([
                    'package_order_id' => $order->id,
                    'name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('orders.show', $token)->with('success', 'Your content has been submitted. We will be in touch shortly.');
    }
}
