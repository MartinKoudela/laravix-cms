@php
    $paddingMap = ['none' => 'py-0', 'sm' => 'py-4', 'md' => 'py-8', 'lg' => 'py-16'];
    $paddingClass = $paddingMap[$padding ?? 'md'] ?? 'py-8';
    $colClass = match ((string) ($columns_count ?? '2')) {
        '3' => 'md:grid-cols-3',
        '4' => 'md:grid-cols-2 lg:grid-cols-4',
        default => 'md:grid-cols-2',
    };
@endphp
<section class="{{ $paddingClass }} {{ $css_class ?? '' }}"
    @if (!empty($background_color)) style="background-color: {{ $background_color }}" @endif>
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        @if (!empty($columns))
            <div class="grid grid-cols-1 {{ $colClass }} gap-8">
                @foreach ($columns as $column)
                    <div>
                        @foreach ($column['blocks'] ?? [] as $nestedBlock)
                            @include(
                                "themes.default::blocks.{$nestedBlock['type']}",
                                array_merge($nestedBlock['data'] ?? [], ['mediaMap' => $mediaMap])
                            )
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
