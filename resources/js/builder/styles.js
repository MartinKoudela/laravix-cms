import {GOOGLE_FONTS} from './constants';

export function preloadFonts() {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = `https://fonts.googleapis.com/css2?${GOOGLE_FONTS.map(f => `family=${encodeURIComponent(f)}:wght@400`).join('&')}&display=swap`;
    document.head.appendChild(link);
}

export function setupStyleManager(editor) {
    editor.StyleManager.addType('integer', {
        create({change}) {
            const wrap = document.createElement('div');
            wrap.style.cssText = 'display:flex;align-items:center;gap:6px;';

            const slider = document.createElement('input');
            slider.type = 'range';
            slider.min = '0';
            slider.max = '500';
            slider.step = '1';
            slider.style.cssText = 'flex:1;min-width:0;cursor:pointer;accent-color:#2563eb;height:4px;';

            const input = document.createElement('input');
            input.type = 'number';
            input.style.cssText = 'width:54px;padding:3px 6px;background:#27272a;border:1px solid #3f3f46;border-radius:6px;color:#e4e4e7;font-size:12px;text-align:center;box-sizing:border-box;';

            slider.addEventListener('input', () => {
                input.value = slider.value;
                change(slider.value);
            });

            input.addEventListener('change', () => {
                slider.value = input.value;
                change(input.value);
            });

            wrap.append(slider, input);
            return wrap;
        },
        update({value, el, property}) {
            const slider = el.querySelector('input[type="range"]');
            const input = el.querySelector('input[type="number"]');
            if (!slider || !input) return;

            const num = parseFloat(value) || 0;
            const unit = property?.getUnit?.() ?? 'px';

            if (unit === 'rem' || unit === 'em') {
                slider.min = '0';
                slider.max = '20';
                slider.step = '0.1';
            } else if (unit === '%' || unit === 'vw' || unit === 'vh') {
                slider.min = '0';
                slider.max = '100';
                slider.step = '1';
            } else {
                slider.min = '0';
                slider.max = '500';
                slider.step = '1';
            }

            if (num > parseFloat(slider.max)) slider.max = String(Math.ceil(num * 1.5));

            slider.value = num;
            input.value = num;
        },
    });

    editor.on('style:property:update', ({property}) => {
        if (property.getId() !== 'font-family') return;
        const val = property.getValue();
        const match = val.match(/'([^']+)'/);
        if (!match) return;
        const fontName = match[1];
        const canvasDoc = editor.Canvas.getDocument();
        const id = `gf-${fontName.replace(/\s+/g, '-')}`;
        if (canvasDoc.getElementById(id)) return;
        const link = canvasDoc.createElement('link');
        link.id = id;
        link.rel = 'stylesheet';
        link.href = `https://fonts.googleapis.com/css2?family=${encodeURIComponent(fontName)}:wght@300;400;500;600;700;800&display=swap`;
        canvasDoc.head.appendChild(link);
    });
}
