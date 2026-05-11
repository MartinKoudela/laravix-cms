import { GOOGLE_FONTS } from './constants';

const FONT_PICKER_OPTIONS = [
    { value: 'inherit',   label: '— Zdědit —', family: 'inherit' },
    ...GOOGLE_FONTS.map(f => ({ value: `'${f}', sans-serif`, label: f, family: `'${f}', sans-serif` })),
    { value: 'serif',     label: 'Serif',      family: 'serif' },
    { value: 'monospace', label: 'Monospace',  family: 'monospace' },
];

export function preloadFonts() {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = `https://fonts.googleapis.com/css2?${GOOGLE_FONTS.map(f => `family=${encodeURIComponent(f)}:wght@400`).join('&')}&display=swap`;
    document.head.appendChild(link);
}

export function setupStyleManager(editor) {
    editor.StyleManager.addType('integer', {
        create({ change }) {
            const wrap = document.createElement('div');
            wrap.style.cssText = 'display:flex;align-items:center;gap:6px;';

            const slider = document.createElement('input');
            slider.type = 'range';
            slider.min = '0'; slider.max = '500'; slider.step = '1';
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
        update({ value, el, property }) {
            const slider = el.querySelector('input[type="range"]');
            const input  = el.querySelector('input[type="number"]');
            if (!slider || !input) return;

            const num  = parseFloat(value) || 0;
            const unit = property?.getUnit?.() ?? 'px';

            if (unit === 'rem' || unit === 'em') {
                slider.min = '0'; slider.max = '20'; slider.step = '0.1';
            } else if (unit === '%' || unit === 'vw' || unit === 'vh') {
                slider.min = '0'; slider.max = '100'; slider.step = '1';
            } else {
                slider.min = '0'; slider.max = '500'; slider.step = '1';
            }

            if (num > parseFloat(slider.max)) slider.max = String(Math.ceil(num * 1.5));

            slider.value = num;
            input.value  = num;
        },
    });

    editor.StyleManager.addType('font-family-picker', {
        create({ change }) {
            const wrap = document.createElement('div');
            wrap.style.cssText = 'position:relative;';

            const trigger = document.createElement('button');
            trigger.type = 'button';
            trigger.style.cssText = 'width:100%;padding:6px 10px;background:#27272a;border:1px solid #3f3f46;border-radius:6px;color:#e4e4e7;cursor:pointer;display:flex;align-items:center;justify-content:space-between;gap:8px;text-align:left;min-height:34px;box-sizing:border-box;';
            trigger.innerHTML = `<span class="fp-label" style="font-family:inherit;font-size:14px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">— Zdědit —</span><i class="fa-solid fa-chevron-down" style="font-size:10px;color:#71717a;flex-shrink:0;"></i>`;

            const dropdown = document.createElement('div');
            dropdown.style.cssText = 'display:none;position:fixed;z-index:99999;background:#18181b;border:1px solid #3f3f46;border-radius:8px;box-shadow:0 8px 32px rgba(0,0,0,.6);width:240px;overflow:hidden;';

            const search = document.createElement('input');
            search.type = 'text';
            search.placeholder = 'Hledat font…';
            search.style.cssText = 'width:100%;padding:8px 12px;background:#27272a;border:none;border-bottom:1px solid #3f3f46;color:#e4e4e7;font-size:12px;box-sizing:border-box;outline:none;';

            const list = document.createElement('div');
            list.style.cssText = 'max-height:280px;overflow-y:auto;';

            const label = trigger.querySelector('.fp-label');
            let isOpen = false;

            FONT_PICKER_OPTIONS.forEach(font => {
                const item = document.createElement('div');
                item.style.cssText = `padding:10px 12px;cursor:pointer;font-size:15px;line-height:1.3;color:#e4e4e7;font-family:${font.family};white-space:nowrap;overflow:hidden;text-overflow:ellipsis;transition:background .1s;`;
                item.dataset.label = font.label;
                item.textContent = font.label;
                item.addEventListener('mouseenter', () => { item.style.background = '#27272a'; });
                item.addEventListener('mouseleave', () => { item.style.background = ''; });
                item.addEventListener('mousedown', (e) => {
                    e.preventDefault();
                    change(font.value);
                    label.textContent = font.label;
                    label.style.fontFamily = font.family;
                    closeDropdown();
                });
                list.appendChild(item);
            });

            search.addEventListener('input', () => {
                const q = search.value.toLowerCase();
                list.querySelectorAll('div').forEach(item => {
                    item.style.display = (item.dataset.label || '').toLowerCase().includes(q) ? '' : 'none';
                });
            });

            dropdown.append(search, list);
            document.body.appendChild(dropdown);

            function openDropdown() {
                const rect = trigger.getBoundingClientRect();
                dropdown.style.left  = `${rect.left}px`;
                dropdown.style.top   = `${rect.bottom + 4}px`;
                dropdown.style.width = `${Math.max(rect.width, 240)}px`;
                dropdown.style.display = 'block';
                isOpen = true;
                requestAnimationFrame(() => search.focus());
            }

            function closeDropdown() {
                dropdown.style.display = 'none';
                isOpen = false;
                search.value = '';
                list.querySelectorAll('div').forEach(item => { item.style.display = ''; });
            }

            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                isOpen ? closeDropdown() : openDropdown();
            });

            document.addEventListener('click', () => { if (isOpen) closeDropdown(); });

            wrap._fpCleanup = () => { dropdown.remove(); };
            wrap.append(trigger);
            return wrap;
        },
        update({ value, el }) {
            const label = el.querySelector('.fp-label');
            if (!label) return;
            const opt = FONT_PICKER_OPTIONS.find(o => o.value === value);
            if (opt) {
                label.textContent = opt.label;
                label.style.fontFamily = opt.family;
            } else if (value) {
                label.textContent = value;
                label.style.fontFamily = value;
            }
        },
        destroy({ el }) {
            el._fpCleanup?.();
        },
    });

    editor.on('style:property:update', ({ property }) => {
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