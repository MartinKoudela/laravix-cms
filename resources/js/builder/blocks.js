const faIcon = (cls) => {
    const prefix = /^fa-(brands|regular|light|thin|duotone)\b/.test(cls) ? '' : 'fa-solid ';
    return `<i class="${prefix}${cls}" style="font-size:1.5rem;display:block;margin:0 auto 4px;"></i>`;
};

export function registerBlocks(editor) {
    const bm = editor.BlockManager;

    // ── Sekce ─────────────────────────────────────────────────────────────────

    bm.add('hero', {
        label: 'Hero',
        category: 'Sekce',
        media: faIcon('fa-house-chimney'),
        content: `
        <section style="padding:80px 24px;text-align:center;background:#f9fafb;">
            <div style="max-width:720px;margin:0 auto;">
                <span style="display:inline-block;padding:4px 14px;background:#eff6ff;color:#2563eb;font-size:.8125rem;font-weight:600;border-radius:999px;margin:0 0 20px;">Novinka</span>
                <h1 style="font-size:3rem;font-weight:800;color:#111827;margin:0 0 16px;line-height:1.15;">Hlavní nadpis stránky</h1>
                <p style="font-size:1.25rem;color:#6b7280;margin:0 0 36px;line-height:1.7;">Krátký popis nebo výzva k akci pro návštěvníky.</p>
                <div style="display:flex;justify-content:center;gap:12px;flex-wrap:wrap;">
                    <a href="#" data-gjs-type="button-link" style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;font-size:.9375rem;font-weight:600;border-radius:8px;text-decoration:none;cursor:pointer;background:#111827;color:#fff;border:2px solid #111827;">Začít</a>
                    <a href="#" data-gjs-type="button-link" style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;font-size:.9375rem;font-weight:600;border-radius:8px;text-decoration:none;cursor:pointer;background:transparent;color:#111827;border:2px solid #111827;">Více info</a>
                </div>
            </div>
        </section>`,
    });

    bm.add('hero-image', {
        label: 'Hero + Foto',
        category: 'Sekce',
        media: faIcon('fa-image'),
        content: `
        <section style="padding:80px 24px;background:#f9fafb;">
            <div style="max-width:1100px;margin:0 auto;display:flex;align-items:center;gap:64px;flex-wrap:wrap;">
                <div style="flex:1;min-width:280px;">
                    <h1 style="font-size:2.75rem;font-weight:800;color:#111827;margin:0 0 16px;line-height:1.2;">Nadpis se skvělým obrázkem</h1>
                    <p style="font-size:1.125rem;color:#6b7280;margin:0 0 32px;line-height:1.7;">Popis produktu nebo služby.</p>
                    <a href="#" data-gjs-type="button-link" style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;font-size:.9375rem;font-weight:600;border-radius:8px;text-decoration:none;cursor:pointer;background:#111827;color:#fff;border:2px solid #111827;">Začít zdarma</a>
                </div>
                <div style="flex:1;min-width:280px;">
                    <img src="https://placehold.co/600x400?text=Foto" data-gjs-type="media-image" style="width:100%;border-radius:16px;display:block;" alt="Hero obrázek">
                </div>
            </div>
        </section>`,
    });

    bm.add('text', {
        label: 'Text',
        category: 'Sekce',
        media: faIcon('fa-align-left'),
        content: `
        <section style="padding:64px 24px;">
            <div style="max-width:720px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;margin:0 0 16px;">Nadpis sekce</h2>
                <p style="font-size:1rem;color:#374151;line-height:1.8;margin:0;">Zde je text sekce. Klikni pro editaci obsahu.</p>
            </div>
        </section>`,
    });

    bm.add('cards', {
        label: 'Karty',
        category: 'Sekce',
        media: faIcon('fa-table-cells-large'),
        content: `
        <section style="padding:64px 24px;background:#f9fafb;">
            <div style="max-width:1100px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 12px;">Sekce karet</h2>
                <p style="font-size:1rem;color:#6b7280;text-align:center;margin:0 0 40px;">Popis sekce.</p>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px;">
                    <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                        <div style="width:48px;height:48px;background:#eff6ff;border-radius:10px;margin:0 0 16px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-bolt" style="color:#2563eb;font-size:1.25rem;"></i></div>
                        <h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0 0 8px;">Funkce 1</h3>
                        <p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">Popis funkce.</p>
                    </div>
                    <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                        <div style="width:48px;height:48px;background:#f0fdf4;border-radius:10px;margin:0 0 16px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-shield-halved" style="color:#16a34a;font-size:1.25rem;"></i></div>
                        <h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0 0 8px;">Funkce 2</h3>
                        <p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">Popis funkce.</p>
                    </div>
                    <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                        <div style="width:48px;height:48px;background:#fdf4ff;border-radius:10px;margin:0 0 16px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-star" style="color:#9333ea;font-size:1.25rem;"></i></div>
                        <h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0 0 8px;">Funkce 3</h3>
                        <p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">Popis funkce.</p>
                    </div>
                </div>
            </div>
        </section>`,
    });

    bm.add('columns', {
        label: 'Sloupce',
        category: 'Sekce',
        media: faIcon('fa-columns'),
        content: `
        <section style="padding:64px 24px;">
            <div style="max-width:1100px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:48px;align-items:center;">
                <div>
                    <h2 style="font-size:2rem;font-weight:700;color:#111827;margin:0 0 16px;">Levý sloupec</h2>
                    <p style="font-size:1rem;color:#374151;line-height:1.8;margin:0 0 24px;">Text levého sloupce.</p>
                    <a href="#" data-gjs-type="button-link" style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;font-size:.9375rem;font-weight:600;border-radius:8px;text-decoration:none;cursor:pointer;background:#111827;color:#fff;border:2px solid #111827;">Více info</a>
                </div>
                <div>
                    <img src="https://placehold.co/600x400?text=Obrázek" data-gjs-type="media-image" style="width:100%;border-radius:12px;display:block;" alt="">
                </div>
            </div>
        </section>`,
    });

    bm.add('cta', {
        label: 'CTA',
        category: 'Sekce',
        media: faIcon('fa-bullhorn'),
        content: `
        <section style="padding:80px 24px;background:#111827;text-align:center;">
            <div style="max-width:600px;margin:0 auto;">
                <h2 style="font-size:2.25rem;font-weight:800;color:#fff;margin:0 0 16px;">Výzva k akci</h2>
                <p style="font-size:1.125rem;color:#9ca3af;margin:0 0 32px;line-height:1.7;">Krátký přesvědčivý text.</p>
                <div style="display:flex;justify-content:center;gap:12px;flex-wrap:wrap;">
                    <a href="#" data-gjs-type="button-link" style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;font-size:.9375rem;font-weight:600;border-radius:8px;text-decoration:none;cursor:pointer;background:#fff;color:#111827;border:2px solid #fff;">Začít</a>
                    <a href="#" data-gjs-type="button-link" style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;font-size:.9375rem;font-weight:600;border-radius:8px;text-decoration:none;cursor:pointer;background:transparent;color:#fff;border:2px solid #4b5563;">Kontakt</a>
                </div>
            </div>
        </section>`,
    });

    bm.add('testimonials', {
        label: 'Recenze',
        category: 'Sekce',
        media: faIcon('fa-comments'),
        content: `
        <section style="padding:64px 24px;background:#f9fafb;">
            <div style="max-width:1100px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 40px;">Co říkají zákazníci</h2>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px;">
                    <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                        <div style="display:flex;gap:2px;margin:0 0 16px;"><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i></div>
                        <p style="font-size:.9375rem;color:#374151;line-height:1.7;margin:0 0 20px;">"Skvělý produkt, doporučuji všem."</p>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="width:40px;height:40px;border-radius:50%;background:#e5e7eb;flex-shrink:0;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-user" style="color:#9ca3af;"></i></div>
                            <div><p style="font-size:.875rem;font-weight:600;color:#111827;margin:0;">Jan Novák</p><p style="font-size:.8125rem;color:#9ca3af;margin:0;">CEO, Firma</p></div>
                        </div>
                    </div>
                    <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                        <div style="display:flex;gap:2px;margin:0 0 16px;"><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i></div>
                        <p style="font-size:.9375rem;color:#374151;line-height:1.7;margin:0 0 20px;">"Úžasná podpora a snadné použití."</p>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="width:40px;height:40px;border-radius:50%;background:#e5e7eb;flex-shrink:0;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-user" style="color:#9ca3af;"></i></div>
                            <div><p style="font-size:.875rem;font-weight:600;color:#111827;margin:0;">Petra Svobodová</p><p style="font-size:.8125rem;color:#9ca3af;margin:0;">Designérka</p></div>
                        </div>
                    </div>
                    <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                        <div style="display:flex;gap:2px;margin:0 0 16px;"><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i></div>
                        <p style="font-size:.9375rem;color:#374151;line-height:1.7;margin:0 0 20px;">"Nejlepší investice tohoto roku."</p>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="width:40px;height:40px;border-radius:50%;background:#e5e7eb;flex-shrink:0;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-user" style="color:#9ca3af;"></i></div>
                            <div><p style="font-size:.875rem;font-weight:600;color:#111827;margin:0;">Martin Dvořák</p><p style="font-size:.8125rem;color:#9ca3af;margin:0;">Podnikatel</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>`,
    });

    bm.add('faq', {
        label: 'FAQ',
        category: 'Sekce',
        media: faIcon('fa-circle-question'),
        content: `
        <section style="padding:64px 24px;">
            <div style="max-width:720px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 40px;">Časté dotazy</h2>
                <div style="display:flex;flex-direction:column;">
                    <div style="border-bottom:1px solid #e5e7eb;padding:20px 0;"><h3 style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 8px;">Jak mohu začít?</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.7;">Stačí se registrovat a ihned začít.</p></div>
                    <div style="border-bottom:1px solid #e5e7eb;padding:20px 0;"><h3 style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 8px;">Je zde bezplatná verze?</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.7;">Ano, nabízíme bezplatný plán.</p></div>
                    <div style="border-bottom:1px solid #e5e7eb;padding:20px 0;"><h3 style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 8px;">Jak funguje podpora?</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.7;">Podpora přes email v pracovní dny.</p></div>
                    <div style="padding:20px 0;"><h3 style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 8px;">Mohu kdykoliv zrušit?</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.7;">Ano, bez smluvních závazků.</p></div>
                </div>
            </div>
        </section>`,
    });

    bm.add('pricing', {
        label: 'Ceník',
        category: 'Sekce',
        media: faIcon('fa-tag'),
        content: `
        <section style="padding:64px 24px;background:#f9fafb;">
            <div style="max-width:1000px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 12px;">Ceník</h2>
                <p style="font-size:1rem;color:#6b7280;text-align:center;margin:0 0 48px;">Vyberte plán, který vám vyhovuje.</p>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px;">
                    <div style="background:#fff;border-radius:16px;padding:32px;border:1px solid #e5e7eb;">
                        <h3 style="font-size:.875rem;font-weight:600;color:#6b7280;margin:0 0 12px;text-transform:uppercase;letter-spacing:.05em;">Starter</h3>
                        <p style="font-size:2.5rem;font-weight:800;color:#111827;margin:0 0 4px;">0 Kč</p>
                        <p style="font-size:.875rem;color:#9ca3af;margin:0 0 24px;">/ měsíc</p>
                        <ul style="list-style:none;padding:0;margin:0 0 28px;display:flex;flex-direction:column;gap:10px;">
                            <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;"><i class="fa-solid fa-check" style="color:#16a34a;"></i> 1 web</li>
                            <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;"><i class="fa-solid fa-check" style="color:#16a34a;"></i> 5 stránek</li>
                            <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;color:#9ca3af;"><i class="fa-solid fa-xmark" style="color:#d1d5db;"></i> Vlastní doména</li>
                        </ul>
                        <a href="#" style="display:block;text-align:center;padding:12px;background:#f9fafb;color:#111827;font-weight:600;border-radius:8px;text-decoration:none;border:1px solid #e5e7eb;">Začít zdarma</a>
                    </div>
                    <div style="background:#111827;border-radius:16px;padding:32px;">
                        <h3 style="font-size:.875rem;font-weight:600;color:#9ca3af;margin:0 0 12px;text-transform:uppercase;letter-spacing:.05em;">Pro</h3>
                        <p style="font-size:2.5rem;font-weight:800;color:#fff;margin:0 0 4px;">490 Kč</p>
                        <p style="font-size:.875rem;color:#6b7280;margin:0 0 24px;">/ měsíc</p>
                        <ul style="list-style:none;padding:0;margin:0 0 28px;display:flex;flex-direction:column;gap:10px;">
                            <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;color:#e5e7eb;"><i class="fa-solid fa-check" style="color:#4ade80;"></i> 5 webů</li>
                            <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;color:#e5e7eb;"><i class="fa-solid fa-check" style="color:#4ade80;"></i> Neomezené stránky</li>
                            <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;color:#e5e7eb;"><i class="fa-solid fa-check" style="color:#4ade80;"></i> Vlastní doména</li>
                        </ul>
                        <a href="#" style="display:block;text-align:center;padding:12px;background:#2563eb;color:#fff;font-weight:600;border-radius:8px;text-decoration:none;">Vybrat Pro</a>
                    </div>
                    <div style="background:#fff;border-radius:16px;padding:32px;border:1px solid #e5e7eb;">
                        <h3 style="font-size:.875rem;font-weight:600;color:#6b7280;margin:0 0 12px;text-transform:uppercase;letter-spacing:.05em;">Enterprise</h3>
                        <p style="font-size:2.5rem;font-weight:800;color:#111827;margin:0 0 4px;">Na míru</p>
                        <p style="font-size:.875rem;color:#9ca3af;margin:0 0 24px;">dle dohody</p>
                        <ul style="list-style:none;padding:0;margin:0 0 28px;display:flex;flex-direction:column;gap:10px;">
                            <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;"><i class="fa-solid fa-check" style="color:#16a34a;"></i> Neomezené weby</li>
                            <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;"><i class="fa-solid fa-check" style="color:#16a34a;"></i> SLA podpora</li>
                            <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;"><i class="fa-solid fa-check" style="color:#16a34a;"></i> Custom integrace</li>
                        </ul>
                        <a href="#" style="display:block;text-align:center;padding:12px;background:#f9fafb;color:#111827;font-weight:600;border-radius:8px;text-decoration:none;border:1px solid #e5e7eb;">Kontaktovat</a>
                    </div>
                </div>
            </div>
        </section>`,
    });

    bm.add('stats', {
        label: 'Statistiky',
        category: 'Sekce',
        media: faIcon('fa-chart-bar'),
        content: `
        <section style="padding:64px 24px;background:#111827;">
            <div style="max-width:900px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:32px;text-align:center;">
                <div><p style="font-size:3rem;font-weight:800;color:#fff;margin:0 0 4px;">10K+</p><p style="font-size:.9375rem;color:#9ca3af;margin:0;">Zákazníků</p></div>
                <div><p style="font-size:3rem;font-weight:800;color:#fff;margin:0 0 4px;">99%</p><p style="font-size:.9375rem;color:#9ca3af;margin:0;">Dostupnost</p></div>
                <div><p style="font-size:3rem;font-weight:800;color:#fff;margin:0 0 4px;">50+</p><p style="font-size:.9375rem;color:#9ca3af;margin:0;">Zemí světa</p></div>
                <div><p style="font-size:3rem;font-weight:800;color:#fff;margin:0 0 4px;">24/7</p><p style="font-size:.9375rem;color:#9ca3af;margin:0;">Podpora</p></div>
            </div>
        </section>`,
    });

    bm.add('gallery', {
        label: 'Galerie',
        category: 'Sekce',
        media: faIcon('fa-images'),
        content: `
        <section style="padding:64px 24px;">
            <div style="max-width:1100px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 40px;">Galerie</h2>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
                    <img src="https://placehold.co/600x400?text=1" data-gjs-type="media-image" style="width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:8px;display:block;" alt="">
                    <img src="https://placehold.co/600x400?text=2" data-gjs-type="media-image" style="width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:8px;display:block;" alt="">
                    <img src="https://placehold.co/600x400?text=3" data-gjs-type="media-image" style="width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:8px;display:block;" alt="">
                    <img src="https://placehold.co/600x400?text=4" data-gjs-type="media-image" style="width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:8px;display:block;" alt="">
                    <img src="https://placehold.co/600x400?text=5" data-gjs-type="media-image" style="width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:8px;display:block;" alt="">
                    <img src="https://placehold.co/600x400?text=6" data-gjs-type="media-image" style="width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:8px;display:block;" alt="">
                </div>
            </div>
        </section>`,
    });

    bm.add('team', {
        label: 'Tým',
        category: 'Sekce',
        media: faIcon('fa-people-group'),
        content: `
        <section style="padding:64px 24px;">
            <div style="max-width:1000px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 40px;">Náš tým</h2>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:24px;">
                    <div style="text-align:center;"><img src="https://placehold.co/200x200?text=Foto" data-gjs-type="media-image" style="width:100px;height:100px;border-radius:50%;margin:0 auto 12px;display:block;object-fit:cover;" alt=""><p style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 4px;">Jan Novák</p><p style="font-size:.875rem;color:#6b7280;margin:0;">CEO &amp; Zakladatel</p></div>
                    <div style="text-align:center;"><img src="https://placehold.co/200x200?text=Foto" data-gjs-type="media-image" style="width:100px;height:100px;border-radius:50%;margin:0 auto 12px;display:block;object-fit:cover;" alt=""><p style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 4px;">Petra Nová</p><p style="font-size:.875rem;color:#6b7280;margin:0;">CTO</p></div>
                    <div style="text-align:center;"><img src="https://placehold.co/200x200?text=Foto" data-gjs-type="media-image" style="width:100px;height:100px;border-radius:50%;margin:0 auto 12px;display:block;object-fit:cover;" alt=""><p style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 4px;">Karel Malý</p><p style="font-size:.875rem;color:#6b7280;margin:0;">Lead Dev</p></div>
                    <div style="text-align:center;"><img src="https://placehold.co/200x200?text=Foto" data-gjs-type="media-image" style="width:100px;height:100px;border-radius:50%;margin:0 auto 12px;display:block;object-fit:cover;" alt=""><p style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 4px;">Jana Veselá</p><p style="font-size:.875rem;color:#6b7280;margin:0;">Head of Design</p></div>
                </div>
            </div>
        </section>`,
    });

    bm.add('contact-form', {
        label: 'Kontakt',
        category: 'Sekce',
        media: faIcon('fa-envelope'),
        content: `
        <section style="padding:64px 24px;background:#f9fafb;">
            <div style="max-width:560px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 8px;">Kontaktujte nás</h2>
                <p style="font-size:1rem;color:#6b7280;text-align:center;margin:0 0 36px;">Odpovídáme do 24 hodin.</p>
                <form data-contact-form style="display:flex;flex-direction:column;gap:16px;">
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
                        <div><label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin:0 0 6px;">Jméno</label><input name="name" type="text" placeholder="Jan Novák" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:.9375rem;box-sizing:border-box;" required></div>
                        <div><label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin:0 0 6px;">E-mail</label><input name="email" type="email" placeholder="jan@example.com" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:.9375rem;box-sizing:border-box;" required></div>
                    </div>
                    <div><label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin:0 0 6px;">Předmět</label><input name="subject" type="text" placeholder="Dotaz ohledně..." style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:.9375rem;box-sizing:border-box;"></div>
                    <div><label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin:0 0 6px;">Zpráva</label><textarea name="message" rows="5" placeholder="Vaše zpráva..." style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:.9375rem;resize:vertical;box-sizing:border-box;" required></textarea></div>
                    <div id="form-status" style="display:none;padding:12px;border-radius:8px;font-size:.9375rem;text-align:center;"></div>
                    <button type="submit" style="padding:14px;background:#111827;color:#fff;font-weight:600;border:none;border-radius:8px;font-size:1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;"><i class="fa-solid fa-paper-plane"></i> Odeslat zprávu</button>
                </form>
            </div>
        </section>`,
    });

    bm.add('video-youtube', {
        label: 'YouTube video',
        category: 'Sekce',
        media: faIcon('fa-brands fa-youtube'),
        content: `
        <section style="padding:64px 24px;background:#f9fafb;">
            <div style="max-width:800px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 32px;">Video</h2>
                <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:12px;">
                    <iframe data-gjs-type="youtube-video" src="https://www.youtube.com/embed/dQw4w9WgXcQ?rel=0" frameborder="0" allowfullscreen allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture" style="position:absolute;top:0;left:0;width:100%;height:100%;"></iframe>
                </div>
            </div>
        </section>`,
    });

    bm.add('video-embed', {
        label: 'Video (MP4)',
        category: 'Sekce',
        media: faIcon('fa-film'),
        content: `
        <section style="padding:64px 24px;">
            <div style="max-width:800px;margin:0 auto;">
                <video data-gjs-type="mp4-video" controls preload="metadata" style="width:100%;border-radius:12px;display:block;"></video>
            </div>
        </section>`,
    });

    bm.add('video-hero', {
        label: 'Video hero',
        category: 'Sekce',
        media: faIcon('fa-circle-play'),
        content: `
        <section style="position:relative;min-height:500px;display:flex;align-items:center;justify-content:center;overflow:hidden;background:#000;">
            <video data-gjs-type="mp4-video" autoplay muted loop playsinline preload="metadata" style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;opacity:0.5;"></video>
            <div style="position:relative;z-index:1;text-align:center;padding:40px 24px;max-width:720px;">
                <h1 style="font-size:3rem;font-weight:800;color:#fff;margin:0 0 16px;line-height:1.15;">Nadpis s video pozadím</h1>
                <p style="font-size:1.25rem;color:rgba(255,255,255,.8);margin:0 0 36px;line-height:1.7;">Stručný popis nebo výzva k akci.</p>
                <a href="#" data-gjs-type="button-link" style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;font-size:.9375rem;font-weight:600;border-radius:8px;text-decoration:none;cursor:pointer;background:#fff;color:#111827;border:2px solid #fff;">Začít</a>
            </div>
        </section>`,
    });

    bm.add('divider', {
        label: 'Oddělovač',
        category: 'Sekce',
        media: faIcon('fa-minus'),
        content: '<div style="padding:32px 24px;"><hr style="border:none;border-top:1px solid #e5e7eb;"></div>',
    });

    // ── Elementy ──────────────────────────────────────────────────────────────

    bm.add('button-primary', {
        label: 'Tlačítko',
        category: 'Elementy',
        media: faIcon('fa-hand-pointer'),
        content: '<a href="#" data-gjs-type="button-link" style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;font-size:.9375rem;font-weight:600;border-radius:8px;text-decoration:none;cursor:pointer;background:#111827;color:#fff;border:2px solid #111827;">Tlačítko</a>',
    });

    bm.add('image-block', {
        label: 'Obrázek',
        category: 'Elementy',
        media: faIcon('fa-image'),
        content: '<img src="https://placehold.co/800x400?text=Obrázek" data-gjs-type="media-image" style="width:100%;height:auto;display:block;border-radius:8px;" alt="">',
    });

    bm.add('image-centered', {
        label: 'Obrázek (střed)',
        category: 'Elementy',
        media: faIcon('fa-expand'),
        content: '<div style="display:flex;justify-content:center;"><img src="https://placehold.co/400x300?text=Obrázek" data-gjs-type="media-image" style="width:400px;max-width:100%;height:auto;display:block;border-radius:8px;" alt=""></div>',
    });

    bm.add('icon-block', {
        label: 'Ikona',
        category: 'Elementy',
        media: faIcon('fa-icons'),
        content: '<i class="fa-solid fa-star" style="font-size:2rem;color:#111827;display:block;"></i>',
    });

    bm.add('badge', {
        label: 'Badge',
        category: 'Elementy',
        media: faIcon('fa-certificate'),
        content: '<span style="display:inline-block;padding:4px 12px;background:#eff6ff;color:#2563eb;font-size:.8125rem;font-weight:600;border-radius:999px;">Novinka</span>',
    });

    bm.add('html-embed-block', {
        label: 'HTML embed',
        category: 'Elementy',
        media: faIcon('fa-code'),
        content: '<div data-gjs-type="html-embed" style="padding:16px;border:2px dashed #e5e7eb;border-radius:8px;text-align:center;color:#9ca3af;font-size:.875rem;"><!-- Klikni a vlož HTML kód --></div>',
    });
}