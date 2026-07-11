@php
    use Laravix\Cms\Enums\ImageVariant;
    $paddingMap = ['none' => 'py-0', 'sm' => 'py-4', 'md' => 'py-8', 'lg' => 'py-16'];
    $paddingClass = $paddingMap[$padding ?? 'md'] ?? 'py-8';
@endphp
<section class="{{ $paddingClass }} {{ $css_class ?? '' }}"
    @if (!empty($background_color)) style="background-color: {{ $background_color }}" @endif>
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        @if (!empty($heading))
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">{{ $heading }}</h2>
        @endif
        @if (!empty($items))
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($items as $item)
                    @php
                        $cardMedia = !empty($item['image_id']) ? ($mediaMap[$item['image_id']] ?? null) : null;
                    @endphp
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        @if ($cardMedia)
                            <img src="{{ $cardMedia->variantUrl(ImageVariant::MEDIUM) }}" alt="{{ $cardMedia->name }}"
                                 class="w-full h-48 object-cover">
                        @endif
                        <div class="p-5">
                            @if (!empty($item['title']))
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ $item['title'] }}</h3>
                            @endif
                            @foreach (is_array($item['blocks'] ?? null) ? $item['blocks'] : [] as $nestedBlock)
                                @include(
                                    "themes.default::blocks.{$nestedBlock['type']}",
                                    array_merge($nestedBlock['data'] ?? [], ['mediaMap' => $mediaMap])
                                )
                            @endforeach
                            @if (!empty($item['link']))
                                <a href="{{ $item['link'] }}"
                                   class="mt-3 inline-block text-sm font-medium text-gray-900 underline hover:text-gray-600">
                                    {{ __('Read more') }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>