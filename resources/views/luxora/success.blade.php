@extends('layouts.app')
@section('content')
<main class="container" style="padding:4rem 0"><h1>Order received</h1><p>Reference #{{ $order->id }}. Payment status: {{ $order->payment_status }}.</p><p>We have not charged a payment provider. Staff will confirm manual payment separately.</p>@if(session('success'))<p role="status">{{ session('success') }}</p>@endif<ul>@foreach($order->items as $item)<li>{{ $item->product_name }} × {{ $item->quantity }}</li>@endforeach</ul>@if($order->status === 'pending' && $order->payment_status === 'pending')<form method="POST" action="{{ route('orders.cancel', $order) }}">@csrf<input type="hidden" name="token" value="{{ $order->public_token }}"><button type="submit">Cancel unpaid order</button></form>@endif</main>
@endsection
