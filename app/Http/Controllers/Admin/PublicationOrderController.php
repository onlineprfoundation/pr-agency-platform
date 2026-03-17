<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PublicationOrderStatusUpdated;
use App\Models\PublicationOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PublicationOrderController extends Controller
{
    public function index()
    {
        $orders = PublicationOrder::with(['publication', 'payment'])->latest()->paginate(20);
        return view('admin.publication-orders.index', compact('orders'));
    }

    public function show(PublicationOrder $order)
    {
        $order->load(['publication', 'payment', 'client', 'documents']);
        return view('admin.publication-orders.show', compact('order'));
    }

    public function update(Request $request, PublicationOrder $order)
    {
        $valid = $request->validate([
            'status' => 'required|in:pending_submission,submitted,in_progress,completed',
            'live_link' => 'nullable|url|max:500',
        ]);

        $oldStatus = $order->status;
        $oldLiveLink = $order->live_link;
        $order->update($valid);

        if ($oldStatus !== $order->status || ($valid['live_link'] ?? null) !== $oldLiveLink) {
            try {
                Mail::to($order->email)->send(new PublicationOrderStatusUpdated($order));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return redirect()->route('admin.publication-orders.show', $order)->with('success', 'Order updated. Customer notified.');
    }
}
