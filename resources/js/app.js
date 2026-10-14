import '../css/luxora-overrides.css';

const ready = () => {
    const body = document.body;
    const toast = document.querySelector('.toast');
    let toastTimer;
    const showToast = (message) => {
        if (!toast) return;
        toast.textContent = message;
        toast.classList.add('is-visible');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toast.classList.remove('is-visible'), 2600);
    };

    document.querySelector('.menu-toggle')?.addEventListener('click', (event) => {
        const button = event.currentTarget;
        const nav = document.querySelector('.site-nav');
        const open = nav?.classList.toggle('is-open');
        button.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    document.querySelectorAll('[data-add-to-cart]').forEach((button) => {
        button.addEventListener('click', () => {
            const count = document.querySelector('.cart-count');
            const next = Number(count?.textContent || 0) + 1;
            if (count) count.textContent = String(next);
            showToast(`${button.dataset.addToCart} added to your bag`);
        });
    });

    document.querySelectorAll('[data-wishlist]').forEach((button) => {
        button.addEventListener('click', () => {
            button.classList.toggle('is-liked');
            const count = document.querySelector('.header-wishlist-count');
            const current = Number(count?.textContent || 0);
            const next = button.classList.contains('is-liked') ? current + 1 : Math.max(0, current - 1);
            if (count) {
                count.textContent = String(next);
                count.hidden = next === 0;
            }
            showToast(button.classList.contains('is-liked') ? 'Added to your wishlist' : 'Removed from your wishlist');
        });
    });

    document.querySelector('[data-newsletter-form]')?.addEventListener('submit', (event) => {
        event.preventDefault();
        event.currentTarget.reset();
        showToast('Thanks — your 10% welcome offer is on its way');
    });

    const filterButtons = document.querySelectorAll('[data-category-filter]');
    const productCards = document.querySelectorAll('[data-product-card]');
    filterButtons.forEach((button) => {
        button.addEventListener('click', () => {
            filterButtons.forEach((item) => item.classList.remove('is-selected'));
            button.classList.add('is-selected');
            const category = button.dataset.categoryFilter;
            let visible = 0;
            productCards.forEach((card) => {
                const show = category === 'all' || card.dataset.category === category;
                card.hidden = !show;
                if (show) visible += 1;
            });
            const range = document.querySelector('#product-range');
            const total = document.querySelector('#product-total');
            if (range) range.textContent = visible ? `1–${visible}` : '0';
            if (total) total.textContent = visible || 0;
        });
    });

    document.querySelector('[data-clear-filters]')?.addEventListener('click', () => {
        document.querySelector('[data-category-filter="all"]')?.click();
        document.querySelectorAll('.filter-group input').forEach((input) => { input.checked = false; });
    });

    const sortSelect = document.querySelector('[data-sort-products]');
    const productGrid = document.querySelector('.product-grid-shop');
    const defaultProductOrder = Array.from(productCards);
    sortSelect?.addEventListener('change', () => {
        const mode = sortSelect.value;
        const sorted = [...defaultProductOrder].sort((left, right) => {
            if (mode === 'price-asc') return Number(left.dataset.price) - Number(right.dataset.price);
            if (mode === 'price-desc') return Number(right.dataset.price) - Number(left.dataset.price);
            return defaultProductOrder.indexOf(left) - defaultProductOrder.indexOf(right);
        });
        sorted.forEach((card) => productGrid?.appendChild(card));
    });

    const paginationPages = Array.from(document.querySelectorAll('[data-pagination-page]'));
    const selectPage = (page) => {
        paginationPages.forEach((button) => {
            const selected = button.dataset.paginationPage === String(page);
            button.classList.toggle('is-current', selected);
            if (selected) button.setAttribute('aria-current', 'page');
            else button.removeAttribute('aria-current');
        });
    };
    paginationPages.forEach((button) => button.addEventListener('click', () => selectPage(button.dataset.paginationPage)));
    document.querySelector('[data-pagination-previous]')?.addEventListener('click', () => {
        const current = Number(document.querySelector('[data-pagination-page].is-current')?.dataset.paginationPage || 1);
        selectPage(Math.max(1, current - 1));
    });
    document.querySelector('[data-pagination-next]')?.addEventListener('click', () => {
        const current = Number(document.querySelector('[data-pagination-page].is-current')?.dataset.paginationPage || 1);
        selectPage(Math.min(paginationPages.length || 1, current + 1));
    });

    document.querySelectorAll('.site-nav a').forEach((link) => {
        link.addEventListener('click', () => document.querySelector('.site-nav')?.classList.remove('is-open'));
    });
};

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', ready);
else ready();
