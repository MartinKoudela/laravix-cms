@php
    $paddingMap = ['none' => 'py-0', 'sm' => 'py-2', 'md' => 'py-6', 'lg' => 'py-12'];
    $paddingClass = $paddingMap[$padding ?? 'md'] ?? 'py-6';
@endphp
<div class="{{ $paddingClass }} {{ $css_class ?? '' }} max-w-6xl mx-auto px-4 sm:px-6"
    @if (!empty($background_color)) style="background-color: {{ $background_color }}" @endif>
    <hr class="border-t border-gray-200">
</div>