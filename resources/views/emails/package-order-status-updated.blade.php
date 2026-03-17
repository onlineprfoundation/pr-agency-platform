<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Update</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #1f2937;">Order Update</h2>
    <p>Hi,</p>
    <p>Your order for <strong>{{ $order->package->name }}</strong> has been updated.</p>
    <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
    @if($order->live_link)
        <p><strong>Live link:</strong> <a href="{{ $order->live_link }}" style="color: #2563eb;">{{ $order->live_link }}</a></p>
    @endif
    <p>View your order: <a href="{{ $order->client_id ? route('portal.orders.show', $order) : route('orders.show', $order->access_token) }}" style="color: #2563eb;">View order</a></p>
    <p style="margin-top: 24px; color: #6b7280; font-size: 14px;">— {{ config('app.name') }}</p>
</body>
</html>
