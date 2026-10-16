@extends('layouts.app')
@section('content')
<main class="container" style="padding:4rem 0"><h1>Checkout</h1><p>Manual/offline payment only. Your order remains pending until staff records payment.</p><form method="post" action="{{ route('checkout.place') }}">@csrf<input type="hidden" name="cart_key" value="{{ $cart->session_key }}"><label>Name <input name="customer_name" required value="{{ old('customer_name') }}"></label><label>Email <input type="email" name="email" required value="{{ old('email') }}"></label><label>Shipping address <textarea name="address" required>{{ old('address') }}</textarea></label><button type="submit">Place order — ${{ number_format($total / 100, 2) }}</button></form></main>
@endsection
