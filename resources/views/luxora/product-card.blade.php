@php($product = $product ?? [])
<article class="product-card product-{{ \Illuminate\Support\Str::slug($product['name'] ?? 'item') }}" data-product-card data-category="{{ $product['category'] ?? '' }}" data-name="{{ $product['name'] ?? '' }}" data-price="{{ $product['price'] ?? 0 }}">
    <a class="product-image" href="{{ route('shop') }}?product={{ urlencode($product['name']) }}"><img src="{{ $product['image'] }}" alt="{{ $product['alt'] }}" loading="lazy"><span class="product-quick-view">Quick view</span></a>
    <div class="product-meta"><h3>{{ $product['name'] }}</h3><button class="wishlist-card-button" type="button" data-wishlist aria-label="Add {{ $product['name'] }} to wishlist">@include('luxora.icon', ['name' => 'heart'])</button></div>
    <div class="price-row"><strong>${{ number_format($product['price'], 2) }}</strong>@if(isset($product['oldPrice']))<del>${{ number_format($product['oldPrice'], 2) }}</del>@endif</div>
    <button class="add-button" type="button" data-add-to-cart="{{ $product['name'] }}">Add to cart</button>
</article>
