<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PackageOrderStatusUpdated;
use App\Models\PackageOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PackageOrderController extends Controller
{
    public function index()
    {
        $orders = PackageOrder::with(['package', 'payment', 'client'])->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(PackageOrder $order)
    {
        $order->load(['package', 'payment', 'client', 'documents']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, PackageOrder $order)
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
                Mail::to($order->email)->send(new PackageOrderStatusUpdated($order));
            } catch (\Throwable $e) {
                // Log but don't fail
                report($e);
            }
        }

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order updated. Customer notified.');
    }
}
