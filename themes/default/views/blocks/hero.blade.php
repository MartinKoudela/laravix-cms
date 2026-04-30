@php
    $imageMedia = !empty($image_id) ? ($mediaMap[$image_id] ?? null) : null;
    $paddingMap = ['none' => 'py-0', 'sm' => 'py-8', 'md' => 'py-16', 'lg' => 'py-24'];
    $paddingClass = $paddingMap[$padding ?? 'md'] ?? 'py-16';
@endphp
<section class="{{ $css_class ?? '' }}"
    @if (!empty($background_color)) style="background-color: {{ $background_color }}" @endif>
    @if ($imageMedia)
        <div class="w-full h-64 sm:h-80 md:h-96 overflow-hidden bg-gray-100">
            <img src="{{ $imageMedia->url }}" alt="{{ $imageMedia->name }}" class="w-full h-full object-cover">
        </div>
    @endif
    <div class="{{ $paddingClass }} max-w-3xl mx-auto px-4 sm:px-6 text-center">
        @if (!empty($heading))
            <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4 leading-tight">{{ $heading }}</h2>
        @endif
        @if (!empty($subheading))
            <p class="text-xl text-gray-500 mb-8 leading-relaxed">{{ $subheading }}</p>
        @endif

        @if (!empty($buttons) && is_array($buttons))
            <div class="flex flex-wrap gap-3 justify-center">
                @foreach($buttons as $btn)
                    @if(!empty($btn['label']) && !empty($btn['href']))
                        @php
                            $variantClasses = match($btn['variant'] ?? 'primary') {
                                'secondary' => 'bg-gray-100 text-gray-900 hover:bg-gray-200',
                                'outline' => 'border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white',
                                default => 'bg-gray-900 text-white hover:bg-gray-700',
                            };
                        @endphp
                        <a href="{{ $btn['href'] }}" class="inline-block px-6 py-3 font-medium rounded-lg transition {{ $variantClasses }}">
                            {{ $btn['label'] }}
                        </a>
                    @endif
                @endforeach
            </div>
        @elseif(!empty($button_label) && !empty($button_url))
            <a href="{{ $button_url }}"
               class="inline-block px-6 py-3 bg-gray-900 text-white font-medium rounded-lg hover:bg-gray-700 transition">
                {{ $button_label }}
            </a>
        @endif
    </div>
</section>