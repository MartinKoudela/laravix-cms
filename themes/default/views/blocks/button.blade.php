@php
    $paddingMap = ['none' => 'py-0', 'sm' => 'py-4', 'md' => 'py-8', 'lg' => 'py-16'];
    $paddingClass = $paddingMap[$padding ?? 'md'] ?? 'py-8';
    $styleClasses = match ($style ?? 'primary') {
        'secondary' => 'bg-gray-100 text-gray-900 hover:bg-gray-200',
        'outline' => 'border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white',
        default => 'bg-gray-900 text-white hover:bg-gray-700',
    };
@endphp
<section class="{{ $paddingClass }} {{ $css_class ?? '' }} text-center"
    @if (!empty($background_color)) style="background-color: {{ $background_color }}" @endif>
    @if (!empty($label) && !empty($url))
        <a href="{{ $url }}"
           class="inline-block px-8 py-3 font-medium rounded-lg transition {{ $styleClasses }}">
            {{ $label }}
        </a>
    @endif
</section>