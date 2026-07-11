@php
    $paddingMap = ['none' => 'py-0', 'sm' => 'py-4', 'md' => 'py-8', 'lg' => 'py-16'];
    $paddingClass = $paddingMap[$padding ?? 'md'] ?? 'py-8';

    $styleClasses = [
        'primary'   => 'inline-block px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition',
        'secondary' => 'inline-block px-6 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition',
        'outline'   => 'inline-block px-6 py-2 border border-gray-800 text-gray-800 rounded hover:bg-gray-100 transition',
    ];
@endphp
@if (!empty($buttons))
    <div class="{{ $paddingClass }} {{ $css_class ?? '' }}"
        @if (!empty($background_color)) style="background-color: {{ $background_color }}" @endif>
        <div class="flex flex-wrap justify-center gap-3">
            @foreach ($buttons as $btn)
                @if (!empty($btn['url']) && !empty($btn['label']))
                    <a href="{{ $btn['url'] }}"
                       class="{{ $styleClasses[$btn['style'] ?? 'primary'] ?? $styleClasses['primary'] }}">
                        {{ $btn['label'] }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>
@endif