import { t } from './trans';

export function setupCanvas(editor, { contactUrl, csrfToken }) {
    editor.on('canvas:frame:load', () => {
        const win = editor.Canvas.getWindow();
        const canvasDoc = editor.Canvas.getDocument();
        if (!canvasDoc) return;

        win?.AOS?.init?.({ once: true });
        attachContactFormHandler(canvasDoc, { contactUrl, csrfToken });

        if (!canvasDoc.getElementById('builder-base')) {
            const style = canvasDoc.createElement('style');
            style.id = 'builder-base';
            style.textContent = [
                '*, *::before, *::after { box-sizing: border-box; }',
                'img, video, iframe { max-width: 100%; }',
                'details > summary { list-style: none; }',
                'details > summary::-webkit-details-marker { display: none; }',
                '.faq-chevron { transition: transform .2s ease; }',
                'details[open] .faq-chevron { transform: rotate(180deg); }',
            ].join(' ');
            canvasDoc.head.appendChild(style);
        }

        initSwipers(canvasDoc, win);
    });

    let swiperTimer;
    editor.on('component:add', () => {
        clearTimeout(swiperTimer);
        swiperTimer = setTimeout(() => {
            initSwipers(editor.Canvas.getDocument(), editor.Canvas.getWindow());
        }, 200);
    });
}

function initSwipers(doc, win) {
    if (!doc || !win?.Swiper) return;
    doc.querySelectorAll('.swiper:not(.swiper-initialized)').forEach(el => {
        let breakpoints;
        try { breakpoints = el.dataset.breakpoints ? JSON.parse(el.dataset.breakpoints) : undefined; } catch {}

        new win.Swiper(el, {
            loop:          el.dataset.loop === 'true',
            centeredSlides: el.dataset.centered === 'true',
            slidesPerView: el.dataset.perView === 'auto' ? 'auto' : (parseFloat(el.dataset.perView) || 1),
            spaceBetween:  parseInt(el.dataset.gap) || 0,
            autoplay:      el.dataset.autoplay ? { delay: parseInt(el.dataset.autoplay), disableOnInteraction: false } : false,
            pagination:    el.querySelector('.swiper-pagination') ? { el: el.querySelector('.swiper-pagination'), clickable: true } : false,
            navigation:    el.querySelector('.swiper-button-next') ? { nextEl: el.querySelector('.swiper-button-next'), prevEl: el.querySelector('.swiper-button-prev') } : false,
            breakpoints,
        });
    });
}

function attachContactFormHandler(doc, { contactUrl, csrfToken }) {
    doc.addEventListener('submit', async (e) => {
        const form = e.target.closest('[data-contact-form]');
        if (!form) return;
        e.preventDefault();

        const btn    = form.querySelector('[type="submit"]');
        const status = form.querySelector('#form-status');
        btn.disabled = true;

        const data = Object.fromEntries(new FormData(form).entries());

        try {
            const res = await fetch(contactUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            });
            if (!res.ok) throw new Error();
            form.reset();
            status.style.display = 'block';
            status.style.background = '#f0fdf4';
            status.style.color = '#15803d';
            status.textContent = t('form_success');
        } catch {
            status.style.display = 'block';
            status.style.background = '#fef2f2';
            status.style.color = '#b91c1c';
            status.textContent = t('form_error');
        } finally {
            btn.disabled = false;
        }
    });
}
