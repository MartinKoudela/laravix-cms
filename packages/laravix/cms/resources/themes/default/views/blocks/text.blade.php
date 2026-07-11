@php
    $paddingMap = ['none' => 'py-0', 'sm' => 'py-4', 'md' => 'py-8', 'lg' => 'py-16'];
    $paddingClass = $paddingMap[$padding ?? 'md'] ?? 'py-8';
@endphp
<section class="{{ $paddingClass }} {{ $css_class ?? '' }}"
    @if (!empty($background_color)) style="background-color: {{ $background_color }}" @endif>
    <div class="max-w-3xl mx-auto px-4 sm:px-6">
        @if (!empty($heading))
            <h2 class="text-3xl font-bold text-gray-900 mb-6">{{ $heading }}</h2>
        @endif
        @if (!empty($content))
            <div class="prose prose-gray max-w-none text-gray-700 leading-relaxed">
                {!! $content !!}
            </div>
        @endif
    </div>
</section>