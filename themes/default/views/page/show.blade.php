@extends('themes.default::layouts.app')

@section('content')
    @php
        use App\Enums\ImageVariant;
        $fields = $content->fields->keyBy('key');
        $body = $fields->get('body')?->value;
        $excerpt = $fields->get('excerpt')?->value;
        $heroImageId = $fields->get('hero_image')?->value;
        $heroMedia = $heroImageId ? ($mediaMap[$heroImageId] ?? null) : null;
        $extraFields = $content->fields->whereNotIn('key', $systemFieldKeys);
        $hasBlocks = !empty($content->blocks);
    @endphp

    @if ($content->grapesjs_html)
        @push('head')
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
            <style>
                .gjs-content *, .gjs-content *::before, .gjs-content *::after { box-sizing: border-box; }
                .gjs-content img, .gjs-content video, .gjs-content iframe { max-width: 100%; }
            </style>
        @endpush
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
            <script>
                document.querySelectorAll('.swiper:not(.swiper-initialized)').forEach(function(el) {
                    var breakpoints;
                    try { breakpoints = el.dataset.breakpoints ? JSON.parse(el.dataset.breakpoints) : undefined; } catch(e) {}
                    new Swiper(el, {
                        loop: el.dataset.loop === 'true',
                        centeredSlides: el.dataset.centered === 'true',
                        slidesPerView: el.dataset.perView === 'auto' ? 'auto' : (parseFloat(el.dataset.perView) || 1),
                        spaceBetween: parseInt(el.dataset.gap) || 0,
                        autoplay: el.dataset.autoplay ? { delay: parseInt(el.dataset.autoplay), disableOnInteraction: false } : false,
                        pagination: el.querySelector('.swiper-pagination') ? { el: el.querySelector('.swiper-pagination'), clickable: true } : false,
                        navigation: el.querySelector('.swiper-button-next') ? { nextEl: el.querySelector('.swiper-button-next'), prevEl: el.querySelector('.swiper-button-prev') } : false,
                        breakpoints: breakpoints,
                    });
                });

                document.querySelectorAll('[data-lx-tabs]').forEach(function(tabs) {
                    tabs.querySelectorAll('.lx-tabs__btn').forEach(function(btn) {
                        btn.addEventListener('click', function() {
                            var target = btn.dataset.tab;
                            tabs.querySelectorAll('.lx-tabs__btn').forEach(function(b) { b.classList.remove('lx-tabs__btn--active'); });
                            tabs.querySelectorAll('.lx-tabs__panel').forEach(function(p) { p.classList.remove('lx-tabs__panel--active'); });
                            btn.classList.add('lx-tabs__btn--active');
                            var panel = tabs.querySelector('#' + target);
                            if (panel) panel.classList.add('lx-tabs__panel--active');
                        });
                    });
                });

                document.querySelectorAll('[data-lx-countdown]').forEach(function(el) {
                    var target = new Date(el.dataset.target).getTime();
                    var pad = function(n) { return String(n).padStart(2, '0'); };
                    var tick = function() {
                        var diff = target - Date.now();
                        if (diff <= 0) return;
                        var d = Math.floor(diff / 86400000);
                        var h = Math.floor((diff % 86400000) / 3600000);
                        var m = Math.floor((diff % 3600000) / 60000);
                        var s = Math.floor((diff % 60000) / 1000);
                        var days = el.querySelector('[data-days]');
                        var hours = el.querySelector('[data-hours]');
                        var mins = el.querySelector('[data-minutes]');
                        var secs = el.querySelector('[data-seconds]');
                        if (days) days.textContent = pad(d);
                        if (hours) hours.textContent = pad(h);
                        if (mins) mins.textContent = pad(m);
                        if (secs) secs.textContent = pad(s);
                    };
                    tick();
                    setInterval(tick, 1000);
                });

                if ('IntersectionObserver' in window) {
                    document.querySelectorAll('[data-lx-counter]').forEach(function(el) {
                        var targetVal = parseInt(el.dataset.target) || 0;
                        var suffix = el.dataset.suffix || '';
                        var obs = new IntersectionObserver(function(entries) {
                            if (!entries[0].isIntersecting) return;
                            obs.disconnect();
                            var duration = 1600, start = performance.now();
                            var step = function(now) {
                                var progress = Math.min((now - start) / duration, 1);
                                var eased = 1 - Math.pow(1 - progress, 3);
                                el.textContent = Math.floor(eased * targetVal) + suffix;
                                if (progress < 1) requestAnimationFrame(step);
                            };
                            requestAnimationFrame(step);
                        }, { threshold: 0.5 });
                        obs.observe(el);
                    });
                }

                document.querySelectorAll('[data-lx-before-after]').forEach(function(el) {
                    var range = el.querySelector('.lx-before-after__range');
                    var after = el.querySelector('.lx-before-after__after');
                    var handle = el.querySelector('.lx-before-after__handle');
                    if (!range || !after) return;
                    var update = function(val) {
                        after.style.clipPath = 'inset(0 ' + (100 - val) + '% 0 0)';
                        if (handle) handle.style.left = val + '%';
                    };
                    update(range.value);
                    range.addEventListener('input', function() { update(range.value); });
                });
            </script>
        @endpush
        <div class="gjs-content" style="isolation:isolate">{!! $content->grapesjs_html !!}</div>

    @elseif ($hasBlocks)

        @foreach ($content->blocks as $block)
            @include("themes.default::blocks.{$block['type']}", array_merge($block['data'] ?? [], ['mediaMap' => $mediaMap]))
        @endforeach
    @else
        @if ($heroMedia)
            <img src="{{ $heroMedia->variantUrl(ImageVariant::LARGE) }}" alt="{{ $heroMedia->name }}" style="width:100%;display:block;">
        @endif

        <div>
            <h1>{{ $content->title }}</h1>

            @if ($excerpt)
                <p>{{ $excerpt }}</p>
            @endif

            @if ($body)
                <div>{!! $body !!}</div>
            @endif

            @if ($extraFields->isNotEmpty())
                <div>
                    @foreach ($extraFields as $field)
                        <div>
                            <dt>{{ $field->key }}</dt>
                            <dd>{{ $field->value }}</dd>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($content->taxonomies->isNotEmpty())
                <div>
                    @foreach ($content->taxonomies as $taxonomy)
                        <span>{{ $taxonomy->name }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
@endsection
