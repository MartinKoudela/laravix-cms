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
        initTabs(canvasDoc);
        initCountdowns(canvasDoc);
        initCounters(canvasDoc, win);
        initBeforeAfter(canvasDoc);
    });

    let interactiveTimer;
    editor.on('component:add', () => {
        clearTimeout(interactiveTimer);
        interactiveTimer = setTimeout(() => {
            const doc = editor.Canvas.getDocument();
            const win = editor.Canvas.getWindow();
            initSwipers(doc, win);
            initTabs(doc);
            initCountdowns(doc);
            initCounters(doc, win);
            initBeforeAfter(doc);
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

function initTabs(doc) {
    if (!doc) return;
    doc.querySelectorAll('[data-lx-tabs]').forEach(tabs => {
        if (tabs._lxTabsInit) return;
        tabs._lxTabsInit = true;
        tabs.querySelectorAll('.lx-tabs__btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.dataset.tab;
                tabs.querySelectorAll('.lx-tabs__btn').forEach(b => b.classList.remove('lx-tabs__btn--active'));
                tabs.querySelectorAll('.lx-tabs__panel').forEach(p => p.classList.remove('lx-tabs__panel--active'));
                btn.classList.add('lx-tabs__btn--active');
                const panel = tabs.querySelector(`#${target}`);
                if (panel) panel.classList.add('lx-tabs__panel--active');
            });
        });
    });
}

function initCountdowns(doc) {
    if (!doc) return;
    doc.querySelectorAll('[data-lx-countdown]').forEach(el => {
        if (el._lxCountdownInit) return;
        el._lxCountdownInit = true;
        const target = new Date(el.dataset.target).getTime();
        const tick = () => {
            const diff = target - Date.now();
            if (diff <= 0) return;
            const d = Math.floor(diff / 86400000);
            const h = Math.floor((diff % 86400000) / 3600000);
            const m = Math.floor((diff % 3600000) / 60000);
            const s = Math.floor((diff % 60000) / 1000);
            const pad = n => String(n).padStart(2, '0');
            const days = el.querySelector('[data-days]');
            const hours = el.querySelector('[data-hours]');
            const mins = el.querySelector('[data-minutes]');
            const secs = el.querySelector('[data-seconds]');
            if (days) days.textContent = pad(d);
            if (hours) hours.textContent = pad(h);
            if (mins) mins.textContent = pad(m);
            if (secs) secs.textContent = pad(s);
        };
        tick();
        setInterval(tick, 1000);
    });
}

function initCounters(doc, win) {
    if (!doc || !win) return;
    const Observer = win.IntersectionObserver;
    if (!Observer) return;
    doc.querySelectorAll('[data-lx-counter]').forEach(el => {
        if (el._lxCounterInit) return;
        el._lxCounterInit = true;
        const target = parseInt(el.dataset.target) || 0;
        const suffix = el.dataset.suffix || '';
        const obs = new Observer(([entry]) => {
            if (!entry.isIntersecting) return;
            obs.disconnect();
            const duration = 1600;
            const start = performance.now();
            const step = (now) => {
                const progress = Math.min((now - start) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.floor(eased * target) + suffix;
                if (progress < 1) win.requestAnimationFrame(step);
            };
            win.requestAnimationFrame(step);
        }, { threshold: 0.5 });
        obs.observe(el);
    });
}

function initBeforeAfter(doc) {
    if (!doc) return;
    doc.querySelectorAll('[data-lx-before-after]').forEach(el => {
        if (el._lxBAInit) return;
        el._lxBAInit = true;
        const range = el.querySelector('.lx-before-after__range');
        const after = el.querySelector('.lx-before-after__after');
        const handle = el.querySelector('.lx-before-after__handle');
        if (!range || !after) return;
        const update = (val) => {
            const pct = 100 - val;
            after.style.clipPath = `inset(0 ${pct}% 0 0)`;
            if (handle) handle.style.left = `${val}%`;
        };
        update(range.value);
        range.addEventListener('input', () => update(range.value));
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
