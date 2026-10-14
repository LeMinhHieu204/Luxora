@extends('layouts.app', ['pageTitle' => $pageTitle])

@section('content')
<section class="hero home-hero">
    <div class="hero-copy">
        <p class="eyebrow">New collection</p>
        <h1>Elevate your style.<br>Live with intention.</h1>
        <p class="hero-description">Timeless pieces for the modern lifestyle.<br>Curated for quality, made for you.</p>
        <a class="button button-dark" href="{{ route('shop') }}">Shop the Collection @include('luxora.icon', ['name' => 'arrow'])</a>
    </div>
    <div class="hero-image" role="img" aria-label="Neutral clothing rack and accessories in a bright interior"></div>
</section>

<section id="categories" class="section category-section container">
    <div class="section-heading"><h2>Shop by Category</h2><a class="text-link" href="{{ route('shop') }}">View all categories @include('luxora.icon', ['name' => 'arrow'])</a></div>
    <div class="category-grid">
        @foreach($categories as $category)
        <a class="category-card" href="{{ route('shop') }}?category={{ urlencode($category['name']) }}">
            <img src="{{ $category['image'] }}" alt="{{ $category['name'] }} category" loading="lazy">
            <span class="category-overlay"></span>
            <span class="category-label"><strong>{{ $category['name'] }}</strong><small>Shop now @include('luxora.icon', ['name' => 'arrow'])</small></span>
        </a>
        @endforeach
    </div>
</section>

<section id="new-arrivals" class="section arrivals-section container">
    <div class="section-heading"><h2>New Arrivals</h2><a class="text-link" href="{{ route('shop') }}#new-arrivals">View all new arrivals @include('luxora.icon', ['name' => 'arrow'])</a></div>
    <div class="product-grid product-grid-home">
        @foreach(array_slice($products, 0, 6) as $product)
            @include('luxora.product-card', ['product' => $product])
        @endforeach
    </div>
</section>

<section id="sale" class="promo-banner container">
    <div class="promo-copy"><p class="eyebrow">Season edit</p><h2>Up to 30% Off</h2><p>On selected styles. Limited time only.</p><a class="button button-dark" href="{{ route('shop') }}#sale">Shop the Sale @include('luxora.icon', ['name' => 'arrow'])</a></div>
    <div class="promo-image" role="img" aria-label="Neutral seasonal clothing and accessories"></div>
</section>

@include('luxora.benefits', ['compact' => true])

<section class="section reviews-section container">
    <div class="section-heading"><h2>What Our Customers Say</h2><a class="text-link" href="#reviews">View all reviews @include('luxora.icon', ['name' => 'arrow'])</a></div>
    <div class="reviews-layout">
        <div class="review-grid" id="reviews">
            @foreach($reviews as $review)
            <article class="review-card"><div class="stars" aria-label="5 out of 5 stars">★★★★★</div><p>“{{ $review['quote'] }}”</p><div class="reviewer"><span class="avatar">{{ $review['initials'] }}</span><span><strong>{{ $review['name'] }}</strong><small>Verified Buyer</small></span></div></article>
            @endforeach
        </div>
        <form class="newsletter-card" data-newsletter-form>
            <h2>Stay in the know</h2><p>Subscribe to get 10% off your first order and be the first to hear about new arrivals and exclusive offers.</p><div class="newsletter-input"><label class="sr-only" for="newsletter-email">Your email address</label><input id="newsletter-email" type="email" placeholder="Enter your email address" required><button class="button button-dark" type="submit">Subscribe</button></div><small>No spam, unsubscribe anytime.</small>
        </form>
    </div>
</section>
@endsection
