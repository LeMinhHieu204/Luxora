<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Luxora Store — timeless fashion and lifestyle pieces curated for quality, style, and everyday elegance.">
    <title>{{ $pageTitle ?? 'Luxora Store' }} — Luxora Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="{{ $bodyClass ?? '' }}">
    <div class="announcement-bar">
        <div class="announcement-inner">
            <span>Free shipping on orders over $50! <span aria-hidden="true">🚚</span></span>
            <nav class="announcement-links" aria-label="Utility navigation">
                <a href="{{ route('about') }}">About Us</a>
                <a href="#footer-contact">Contact</a>
                <a href="#faq">FAQ</a>
                <button class="currency-toggle" type="button" aria-label="Currency selector">🇺🇸 US ($) <span aria-hidden="true">⌄</span></button>
            </nav>
        </div>
    </div>

    <header class="site-header">
        <div class="header-inner">
            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-nav" aria-label="Open menu"><span></span><span></span><span></span></button>
            <a class="brand" href="{{ route('home') }}" aria-label="Luxora Store home">
                <span class="brand-word">LUXORA</span>
                <span class="brand-subtitle">STORE</span>
            </a>
            <nav id="site-nav" class="site-nav" aria-label="Main navigation">
                <a class="{{ request()->routeIs('home') ? 'is-active' : '' }}" href="{{ route('home') }}">Home</a>
                <a class="{{ request()->routeIs('shop') ? 'is-active' : '' }}" href="{{ route('shop') }}">Shop <span aria-hidden="true">⌄</span></a>
                <a href="{{ route('shop') }}#categories">Categories <span aria-hidden="true">⌄</span></a>
                <a href="{{ route('shop') }}#new-arrivals">New Arrivals</a>
                <a href="{{ route('shop') }}#sale">Deals</a>
                <a href="#journal">Blog</a>
            </nav>
            <div class="header-actions">
                <form class="search-form" action="{{ route('shop') }}" method="get">
                    <label class="sr-only" for="site-search">Search products</label>
                    <input id="site-search" name="q" type="search" placeholder="Search products...">
                    @include('luxora.icon', ['name' => 'search'])
                </form>
                <a class="icon-button account-button" href="#account" aria-label="Account">@include('luxora.icon', ['name' => 'user'])</a>
                <button class="icon-button wishlist-button" type="button" aria-label="Wishlist">@include('luxora.icon', ['name' => 'heart'])<span class="header-wishlist-count" hidden>0</span></button>
                <button class="icon-button cart-button" type="button" aria-label="Shopping bag">@include('luxora.icon', ['name' => 'bag'])<span class="cart-count">0</span></button>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer id="footer-contact" class="site-footer">
        <div class="footer-main container">
            <div class="footer-brand">
                <a class="brand" href="{{ route('home') }}"><span class="brand-word">LUXORA</span><span class="brand-subtitle">STORE</span></a>
                <p>Timeless fashion and lifestyle products curated for quality, style, and everyday elegance.</p>
                <div class="social-links" aria-label="Social links"><a href="#instagram">◎</a><a href="#facebook">f</a><a href="#pinterest">p</a><a href="#tiktok">♪</a></div>
            </div>
            <div class="footer-column"><h2>Shop</h2><a href="{{ route('shop') }}">All Products</a><a href="{{ route('shop') }}#new-arrivals">New Arrivals</a><a href="{{ route('shop') }}">Best Sellers</a><a href="{{ route('shop') }}#sale">Deals</a><a href="#gift-cards">Gift Cards</a></div>
            <div class="footer-column"><h2>Categories</h2><a href="{{ route('shop') }}">Women</a><a href="{{ route('shop') }}">Men</a><a href="{{ route('shop') }}">Accessories</a><a href="{{ route('shop') }}">Beauty</a><a href="{{ route('shop') }}">Home &amp; Living</a></div>
            <div class="footer-column"><h2>Customer Care</h2><a href="#contact">Contact Us</a><a href="#shipping">Shipping &amp; Delivery</a><a href="#returns">Returns &amp; Exchanges</a><a href="#faq">FAQ</a><a href="#size-guide">Size Guide</a></div>
            <div class="footer-column"><h2>About Us</h2><a href="{{ route('about') }}">Our Story</a><a href="{{ route('about') }}#sustainability">Sustainability</a><a href="#careers">Careers</a><a href="#journal">Blog</a><a href="#press">Press</a></div>
            <div class="footer-column footer-contact"><h2>Contact Us</h2><a href="mailto:help@luxorastore.com">✉&nbsp; help@luxorastore.com</a><a href="tel:+15561234567">♧&nbsp; +1 (555) 123-4567</a><span>⌖&nbsp; 321 Fashion Ave,<br>New York, NY 10001, USA</span><div class="payments"><span>VISA</span><span>●●</span><span>AMEX</span><span>◼</span><span>Pay</span></div></div>
        </div>
        <div class="footer-bottom container"><span>© {{ date('Y') }} Luxora Store. All rights reserved.</span><div><a href="#privacy">Privacy Policy</a><a href="#terms">Terms of Service</a><a href="#accessibility">Accessibility</a></div></div>
    </footer>
    <div class="toast" role="status" aria-live="polite"></div>
</body>
</html>
