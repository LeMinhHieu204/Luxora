@php($name = $name ?? '')
@switch($name)
    @case('search') <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6.7"></circle><path d="m16 16 4.2 4.2"></path></svg> @break
    @case('user') <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.3"></circle><path d="M5.2 20c.5-3.3 2.8-5.2 6.8-5.2s6.3 1.9 6.8 5.2"></path></svg> @break
    @case('heart') <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 8.8c0 5.2-8.8 10-8.8 10s-8.8-4.8-8.8-10A4.3 4.3 0 0 1 12 6.2a4.3 4.3 0 0 1 8.8 2.6Z"></path></svg> @break
    @case('bag') <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 8.7h14l-1 11H6l-1-11Z"></path><path d="M9 9V6.8a3 3 0 0 1 6 0V9"></path></svg> @break
    @case('arrow') <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 12h15"></path><path d="m13 6 6 6-6 6"></path></svg> @break
    @case('truck') <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h11v10H3zM14 10h4l3 3v3h-7z"></path><circle cx="7" cy="18" r="1.8"></circle><circle cx="18" cy="18" r="1.8"></circle></svg> @break
    @case('shield') <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 19 6v5.5c0 4.4-2.8 7.7-7 9.5-4.2-1.8-7-5.1-7-9.5V6l7-3Z"></path><path d="m9 12 2 2 4-4"></path></svg> @break
    @case('refresh') <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 8V4l-2.2 2.2A7.2 7.2 0 1 0 19 14"></path></svg> @break
    @case('headphones') <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 14v-2a8 8 0 0 1 16 0v2"></path><path d="M4 14h3v5H4zM17 14h3v5h-3z"></path></svg> @break
    @case('badge') <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 3 2 1.4 2.5-.2 1 2.3 2.2 1.3-.7 2.4.7 2.4-2.2 1.3-1 2.3-2.5-.2-2 1.4-2-1.4-2.5.2-1-2.3-2.2-1.3.7-2.4-.7-2.4 2.2-1.3 1-2.3 2.5.2L12 3Z"></path><path d="m9.1 11.8 1.9 1.9 3.9-4"></path></svg> @break
@endswitch
