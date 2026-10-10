{{--
    Turns the per-site header/footer design (Navigation → Design) into CSS
    custom properties. Only values the editor actually set are emitted, so
    everything left empty falls back to Noir's own defaults in app.css.
--}}
@php
    $noirHeaderDesign = collect($navDesign['header'] ?? [])->reject(fn ($value) => $value === null || $value === '');
    $noirFooterDesign = collect($navDesign['footer'] ?? [])->reject(fn ($value) => $value === null || $value === '');

    $noirGoogleFonts = [
        'Inter' => 'Inter:wght@300;400;500;600;700',
        'Roboto' => 'Roboto:wght@300;400;500;700',
        'Open Sans' => 'Open+Sans:wght@300;400;500;600;700',
        'Lato' => 'Lato:wght@300;400;700',
        'Montserrat' => 'Montserrat:wght@300;400;500;600;700',
        'Poppins' => 'Poppins:wght@300;400;500;600;700',
        'Nunito' => 'Nunito:wght@300;400;500;600;700',
        'Raleway' => 'Raleway:wght@300;400;500;600;700',
        'Ubuntu' => 'Ubuntu:wght@300;400;500;700',
        'Rubik' => 'Rubik:wght@300;400;500;600;700',
        'Work Sans' => 'Work+Sans:wght@300;400;500;600;700',
        'DM Sans' => 'DM+Sans:wght@300;400;500;600;700',
        'Noto Sans' => 'Noto+Sans:wght@300;400;500;600;700',
        'Source Sans 3' => 'Source+Sans+3:wght@300;400;500;600;700',
        'Manrope' => 'Manrope:wght@300;400;500;600;700',
        'Outfit' => 'Outfit:wght@300;400;500;600;700',
        'Plus Jakarta Sans' => 'Plus+Jakarta+Sans:wght@300;400;500;600;700',
        'Playfair Display' => 'Playfair+Display:wght@400;500;600;700',
        'Merriweather' => 'Merriweather:wght@300;400;700',
        'Lora' => 'Lora:wght@400;500;600;700',
        'PT Serif' => 'PT+Serif:wght@400;700',
        'Libre Baskerville' => 'Libre+Baskerville:wght@400;700',
        'EB Garamond' => 'EB+Garamond:wght@400;500;600;700',
        'Cormorant Garamond' => 'Cormorant+Garamond:wght@300;400;500;600;700',
        'Crimson Text' => 'Crimson+Text:wght@400;600;700',
    ];

    $noirFontsToLoad = collect([$noirHeaderDesign->get('font_family'), $noirFooterDesign->get('font_family')])
        ->filter()
        ->flatMap(fn ($font) => collect($noirGoogleFonts)->filter(fn ($_, $name) => str_starts_with($font, $name))->values())
        ->unique();

    $noirHeaderBackground = null;
    if ($background = $noirHeaderDesign->get('bg_color')) {
        $hex = ltrim($background, '#');
        $hex = strlen($hex) === 3 ? $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2] : $hex;
        $alpha = round(max(0, min(100, (int) $noirHeaderDesign->get('bg_opacity', 100))) / 100, 2);
        $noirHeaderBackground = sprintf('rgba(%d,%d,%d,%s)', hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2)), $alpha);
    }

    $noirShadows = [
        'shadow_sm' => '0 1px 0 rgba(255,255,255,.04), 0 4px 12px rgba(0,0,0,.4)',
        'shadow_md' => '0 1px 0 rgba(255,255,255,.05), 0 8px 24px rgba(0,0,0,.5)',
        'shadow_lg' => '0 1px 0 rgba(255,255,255,.06), 0 16px 48px rgba(0,0,0,.6)',
    ];

    $px = fn ($value) => $value !== null ? (int) $value.'px' : null;

    $noirHeaderVars = array_filter([
        '--noir-header-bg' => $noirHeaderBackground,
        '--noir-header-text' => $noirHeaderDesign->get('text_color'),
        '--noir-header-hover' => $noirHeaderDesign->get('hover_color'),
        '--noir-header-active' => $noirHeaderDesign->get('active_color'),
        '--noir-header-border-color' => $noirHeaderDesign->get('border_color'),
        '--noir-header-border-width' => $noirHeaderDesign->get('border_width'),
        '--noir-header-shadow' => $noirShadows[$noirHeaderDesign->get('shadow')] ?? null,
        '--noir-header-height' => $px($noirHeaderDesign->get('height')),
        '--noir-header-logo-height' => $px($noirHeaderDesign->get('logo_height')),
        '--noir-header-gap' => $px($noirHeaderDesign->get('links_gap')),
        '--noir-header-font' => $noirHeaderDesign->get('font_family'),
        '--noir-header-size' => $px($noirHeaderDesign->get('font_size')),
        '--noir-header-weight' => $noirHeaderDesign->get('font_weight'),
        '--noir-dropdown-bg' => $noirHeaderDesign->get('dropdown_bg'),
        '--noir-dropdown-text' => $noirHeaderDesign->get('dropdown_text'),
        '--noir-dropdown-hover-bg' => $noirHeaderDesign->get('dropdown_hover_bg'),
    ]);

    $noirFooterVars = array_filter([
        '--noir-footer-bg' => $noirFooterDesign->get('bg_color'),
        '--noir-footer-text' => $noirFooterDesign->get('text_color'),
        '--noir-footer-hover' => $noirFooterDesign->get('hover_color'),
        '--noir-footer-border-color' => $noirFooterDesign->get('border_color'),
        '--noir-footer-font' => $noirFooterDesign->get('font_family'),
        '--noir-footer-size' => $px($noirFooterDesign->get('font_size')),
        '--noir-footer-weight' => $noirFooterDesign->get('font_weight'),
        '--noir-footer-padding' => $px($noirFooterDesign->get('padding_y')),
    ]);

    $declarations = fn (array $vars) => collect($vars)
        ->map(fn ($value, $name) => $name.':'.str_replace(['<', '>', '{', '}', ';'], '', (string) $value))
        ->implode(';');
@endphp

@if ($noirFontsToLoad->isNotEmpty())
    <link href="https://fonts.googleapis.com/css2?family={{ $noirFontsToLoad->implode('&family=') }}&display=swap" rel="stylesheet">
@endif

@if ($noirHeaderVars || $noirFooterVars)
    <style>
        @if ($noirHeaderVars).noir-header{ {!! $declarations($noirHeaderVars) !!} }@endif
        @if ($noirFooterVars).noir-footer{ {!! $declarations($noirFooterVars) !!} }@endif
    </style>
@endif
