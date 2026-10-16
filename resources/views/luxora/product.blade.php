@extends('layouts.app')
@section('content')
<main class="container" style="padding:4rem 0"><p><a href="{{ route('shop') }}">← Shop</a></p><h1>{{ $product['name'] }}</h1><p>{{ $product['category'] }} · ${{ number_format($product['price'], 2) }}</p><p>{{ $product['stock'] }} available</p><p>{{ $product['alt'] }}</p><form method="post" action="{{ route('cart.add') }}">@csrf<select name="variant_id" aria-label="Variant">@foreach($variants as $variant)<option value="{{ $variant->id }}">{{ $variant->name }} ({{ $variant->stock }} available)</option>@endforeach</select><input type="number" name="quantity" min="1" max="99" value="1"><button type="submit">Add to cart</button></form></main>
@endsection
