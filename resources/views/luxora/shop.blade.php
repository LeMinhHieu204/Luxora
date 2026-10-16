@extends('layouts.app', ['pageTitle' => $pageTitle, 'bodyClass' => 'shop-page'])

@section('content')
<section class="page-hero shop-page-hero">
    <div class="container page-hero-inner"><div class="page-hero-copy"><div class="breadcrumbs"><a href="{{ route('home') }}">Home</a><span>›</span><strong>Shop</strong></div><h1>Shop Our Collection</h1><p>Timeless fashion and lifestyle pieces curated for quality,<br class="desktop-only"> style, and everyday elegance.</p></div><div class="page-hero-image" role="img" aria-label="Neutral clothing rack with handbag and plant"></div></div>
</section>
<section class="shop-content container">
    <aside class="filter-sidebar" aria-label="Product filters">
        <div class="filter-group filter-categories"><div class="filter-title"><h2>Categories</h2><span>−</span></div><button class="filter-option is-selected" data-category-filter="all">All Categories <span>(48)</span></button><button class="filter-option" data-category-filter="Women">Women <span>(18)</span></button><button class="filter-option" data-category-filter="Men">Men <span>(12)</span></button><button class="filter-option" data-category-filter="Accessories">Accessories <span>(8)</span></button><button class="filter-option" data-category-filter="Beauty">Beauty <span>(5)</span></button><button class="filter-option" data-category-filter="Footwear">Footwear <span>(3)</span></button><button class="filter-option" data-category-filter="Home">Home &amp; Living <span>(2)</span></button></div>
        <div class="filter-group"><div class="filter-title"><h2>Price Range</h2><span>−</span></div><div class="range-line"><span></span><span></span></div><div class="range-labels"><span>$20</span><span>$500</span></div></div>
        <div class="filter-group"><div class="filter-title"><h2>Sizes</h2><span>−</span></div><label><input type="checkbox"> XS</label><label><input type="checkbox"> S</label><label><input type="checkbox"> M</label><label><input type="checkbox"> L</label><label><input type="checkbox"> XL</label><label><input type="checkbox"> XXL</label></div>
        <div class="filter-group"><div class="filter-title"><h2>Colors</h2><span>−</span></div><div class="color-swatches"><i class="swatch beige"></i><i class="swatch caramel"></i><i class="swatch black"></i><i class="swatch brown"></i><i class="swatch grey"></i><i class="swatch white"></i></div></div>
        <div class="filter-group"><div class="filter-title"><h2>Availability</h2><span>−</span></div><label><input type="checkbox"> In Stock <span>(42)</span></label><label><input type="checkbox"> Out of Stock <span>(6)</span></label></div>
        <button class="clear-filters" type="button" data-clear-filters>Clear All Filters</button>
    </aside>
    <div class="shop-results">
        <div class="shop-toolbar"><span>Showing <strong id="product-range">{{ $resultCount ? '1–'.$resultCount : '0' }}</strong> of <strong id="product-total">{{ $catalogTotal }}</strong> products</span><label>Sort by: <select aria-label="Sort products" data-sort-products><option value="newest">Newest First</option><option value="price-asc">Price: Low to High</option><option value="price-desc">Price: High to Low</option></select></label></div>
        <div id="new-arrivals" class="product-grid product-grid-shop">
            @foreach($products as $product)
                @include('luxora.product-card', ['product' => $product])
            @endforeach
        </div>
        @if ($products->isEmpty())
            <p class="empty-state" data-search-empty>No products match “{{ $query }}”. Try another search.</p>
        @endif
        <section id="sale" class="promo-banner shop-promo"><div class="promo-copy"><p class="eyebrow">Limited time offer</p><h2>Up to 30% Off</h2><p>On selected styles. Elevate your wardrobe today.</p><a class="button button-dark" href="#new-arrivals">Shop the Sale @include('luxora.icon', ['name' => 'arrow'])</a></div><div class="promo-image" role="img" aria-label="Neutral seasonal clothing and accessories"></div></section>
    </div>
</section>
@include('luxora.benefits')
<nav class="pagination" aria-label="Pagination"><button type="button" aria-label="Previous page" data-pagination-previous>‹</button><button type="button" class="is-current" data-pagination-page="1" aria-current="page">1</button><button type="button" data-pagination-page="2">2</button><button type="button" data-pagination-page="3">3</button><button type="button" data-pagination-page="4">4</button><button type="button" aria-label="Next page" data-pagination-next>›</button></nav>
@endsection
