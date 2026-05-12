import { t } from './trans';

const faIcon = (cls) => {
    const prefix = /^fa-(brands|regular|light|thin|duotone)\b/.test(cls) ? '' : 'fa-solid ';
    return `<i class="${prefix}${cls}" style="font-size:1.5rem;display:block;margin:0 auto 4px;"></i>`;
};

export function registerBlocks(editor) {
    const bm = editor.BlockManager;


    bm.add('hero', {
        label: t('block_hero_text', 'Hero – text'),
        category: t('cat_hero', 'Hero'),
        media: faIcon('fa-house-chimney'),
        content: `
        <section style="padding:80px 24px;text-align:center;background:#f9fafb;">
            <div style="max-width:720px;margin:0 auto;">
                <span style="display:inline-block;padding:4px 14px;background:#eff6ff;color:#2563eb;font-size:.8125rem;font-weight:600;border-radius:999px;margin:0 0 20px;">${t('c_new_badge', 'New')}</span>
                <h1 style="font-size:3rem;font-weight:800;color:#111827;margin:0 0 16px;line-height:1.15;">${t('c_hero_heading', 'Main page heading')}</h1>
                <p style="font-size:1.25rem;color:#6b7280;margin:0 0 36px;line-height:1.7;">${t('c_hero_subtext', 'Short description or call to action for visitors.')}</p>
                <div style="display:flex;justify-content:center;gap:12px;flex-wrap:wrap;">
                    <a href="#" data-gjs-type="button-link" style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;font-size:.9375rem;font-weight:600;border-radius:8px;text-decoration:none;cursor:pointer;background:#111827;color:#fff;border:2px solid #111827;">${t('c_btn_start', 'Get started')}</a>
                    <a href="#" data-gjs-type="button-link" style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;font-size:.9375rem;font-weight:600;border-radius:8px;text-decoration:none;cursor:pointer;background:transparent;color:#111827;border:2px solid #111827;">${t('c_btn_more_info', 'More info')}</a>
                </div>
            </div>
        </section>`,
    });

    bm.add('hero-image', {
        label: t('block_hero_image', 'Hero + Photo'),
        category: t('cat_hero', 'Hero'),
        media: faIcon('fa-image'),
        content: `
        <section style="padding:80px 24px;background:#f9fafb;">
            <div style="max-width:1100px;margin:0 auto;display:flex;align-items:center;gap:64px;flex-wrap:wrap;">
                <div style="flex:1;min-width:280px;">
                    <h1 style="font-size:2.75rem;font-weight:800;color:#111827;margin:0 0 16px;line-height:1.2;">${t('c_hero_img_heading', 'Heading with a great image')}</h1>
                    <p style="font-size:1.125rem;color:#6b7280;margin:0 0 32px;line-height:1.7;">${t('c_product_desc', 'Product or service description.')}</p>
                    <a href="#" data-gjs-type="button-link" style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;font-size:.9375rem;font-weight:600;border-radius:8px;text-decoration:none;cursor:pointer;background:#111827;color:#fff;border:2px solid #111827;">${t('c_btn_start_free', 'Start for free')}</a>
                </div>
                <div style="flex:1;min-width:280px;">
                    <img src="https://placehold.co/600x400?text=Foto" data-gjs-type="media-image" style="width:100%;border-radius:16px;display:block;" alt="${t('c_hero_img_heading', 'Heading with a great image')}">
                </div>
            </div>
        </section>`,
    });

    bm.add('video-hero', {
        label: t('block_hero_video', 'Hero with video'),
        category: t('cat_hero', 'Hero'),
        media: faIcon('fa-circle-play'),
        content: `
        <section style="position:relative;min-height:500px;display:flex;align-items:center;justify-content:center;overflow:hidden;background:#000;">
            <video data-gjs-type="mp4-video" autoplay muted loop playsinline preload="metadata" style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;opacity:0.5;"></video>
            <div style="position:relative;z-index:1;text-align:center;padding:40px 24px;max-width:720px;">
                <h1 style="font-size:3rem;font-weight:800;color:#fff;margin:0 0 16px;line-height:1.15;">${t('c_hero_video_heading', 'Heading with video background')}</h1>
                <p style="font-size:1.25rem;color:rgba(255,255,255,.8);margin:0 0 36px;line-height:1.7;">${t('c_brief_desc', 'Brief description or call to action.')}</p>
                <a href="#" data-gjs-type="button-link" style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;font-size:.9375rem;font-weight:600;border-radius:8px;text-decoration:none;cursor:pointer;background:#fff;color:#111827;border:2px solid #fff;">${t('c_btn_start', 'Get started')}</a>
            </div>
        </section>`,
    });


    bm.add('text', {
        label: t('block_text', 'Text section'),
        category: t('cat_content', 'Content'),
        media: faIcon('fa-align-left'),
        content: `
        <section style="padding:64px 24px;">
            <div style="max-width:720px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;margin:0 0 16px;">${t('c_section_heading', 'Section heading')}</h2>
                <p style="font-size:1rem;color:#374151;line-height:1.8;margin:0;">${t('c_section_text', 'This is section text. Click to edit content.')}</p>
            </div>
        </section>`,
    });

    bm.add('columns', {
        label: t('block_columns', 'Two columns'),
        category: t('cat_content', 'Content'),
        media: faIcon('fa-columns'),
        content: `
        <section style="padding:64px 24px;">
            <div style="max-width:1100px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:48px;align-items:center;">
                <div>
                    <h2 style="font-size:2rem;font-weight:700;color:#111827;margin:0 0 16px;">${t('c_left_col_heading', 'Left column')}</h2>
                    <p style="font-size:1rem;color:#374151;line-height:1.8;margin:0 0 24px;">${t('c_left_col_text', 'Left column text.')}</p>
                    <a href="#" data-gjs-type="button-link" style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;font-size:.9375rem;font-weight:600;border-radius:8px;text-decoration:none;cursor:pointer;background:#111827;color:#fff;border:2px solid #111827;">${t('c_btn_more_info', 'More info')}</a>
                </div>
                <div>
                    <img src="https://placehold.co/600x400?text=Foto" data-gjs-type="media-image" style="width:100%;border-radius:12px;display:block;" alt="">
                </div>
            </div>
        </section>`,
    });

    bm.add('cards', {
        label: t('block_cards', 'Features / Cards'),
        category: t('cat_content', 'Content'),
        media: faIcon('fa-table-cells-large'),
        content: `
        <section style="padding:64px 24px;background:#f9fafb;">
            <div style="max-width:1100px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 12px;">${t('c_features_heading', 'What we offer')}</h2>
                <p style="font-size:1rem;color:#6b7280;text-align:center;margin:0 0 40px;">${t('c_features_subtext', 'Brief description of product benefits or features.')}</p>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px;">
                    <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                        <div style="width:48px;height:48px;background:#eff6ff;border-radius:10px;margin:0 0 16px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-bolt" style="color:#2563eb;font-size:1.25rem;"></i></div>
                        <h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0 0 8px;">${t('c_speed', 'Speed')}</h3>
                        <p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">${t('c_feature_desc', 'Feature or benefit description.')}</p>
                    </div>
                    <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                        <div style="width:48px;height:48px;background:#f0fdf4;border-radius:10px;margin:0 0 16px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-shield-halved" style="color:#16a34a;font-size:1.25rem;"></i></div>
                        <h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0 0 8px;">${t('c_security', 'Security')}</h3>
                        <p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">${t('c_feature_desc', 'Feature or benefit description.')}</p>
                    </div>
                    <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                        <div style="width:48px;height:48px;background:#fdf4ff;border-radius:10px;margin:0 0 16px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-star" style="color:#9333ea;font-size:1.25rem;"></i></div>
                        <h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0 0 8px;">${t('c_quality', 'Quality')}</h3>
                        <p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">${t('c_feature_desc', 'Feature or benefit description.')}</p>
                    </div>
                </div>
            </div>
        </section>`,
    });

    bm.add('steps', {
        label: t('block_steps', 'Process steps'),
        category: t('cat_content', 'Content'),
        media: faIcon('fa-list-ol'),
        content: `
        <section style="padding:64px 24px;">
            <div style="max-width:900px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 12px;">${t('c_how_it_works', 'How it works')}</h2>
                <p style="font-size:1rem;color:#6b7280;text-align:center;margin:0 0 48px;">${t('c_steps_subtext', 'Three simple steps to the result.')}</p>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:32px;">
                    <div style="text-align:center;">
                        <div style="width:56px;height:56px;background:#eff6ff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:1.25rem;font-weight:700;color:#2563eb;">1</div>
                        <h3 style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 8px;">${t('c_step1_title', 'Registration')}</h3>
                        <p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">${t('c_step1_text', 'Create your account in 30 seconds.')}</p>
                    </div>
                    <div style="text-align:center;">
                        <div style="width:56px;height:56px;background:#eff6ff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:1.25rem;font-weight:700;color:#2563eb;">2</div>
                        <h3 style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 8px;">${t('c_step2_title', 'Setup')}</h3>
                        <p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">${t('c_step2_text', 'Customize your environment.')}</p>
                    </div>
                    <div style="text-align:center;">
                        <div style="width:56px;height:56px;background:#eff6ff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:1.25rem;font-weight:700;color:#2563eb;">3</div>
                        <h3 style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 8px;">${t('c_step3_title', 'Launch')}</h3>
                        <p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">${t('c_step3_text', 'Publish and track results.')}</p>
                    </div>
                </div>
            </div>
        </section>`,
    });

    bm.add('stats', {
        label: t('block_stats', 'Numbers & Statistics'),
        category: t('cat_content', 'Content'),
        media: faIcon('fa-chart-bar'),
        content: `
        <section style="padding:64px 24px;background:#111827;">
            <div style="max-width:900px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:32px;text-align:center;">
                <div><p style="font-size:3rem;font-weight:800;color:#fff;margin:0 0 4px;">10,000+</p><p style="font-size:.9375rem;color:#9ca3af;margin:0;">${t('c_stat1_label', 'Happy customers')}</p></div>
                <div><p style="font-size:3rem;font-weight:800;color:#fff;margin:0 0 4px;">99.9%</p><p style="font-size:.9375rem;color:#9ca3af;margin:0;">${t('c_stat2_label', 'Service uptime')}</p></div>
                <div><p style="font-size:3rem;font-weight:800;color:#fff;margin:0 0 4px;">50+</p><p style="font-size:.9375rem;color:#9ca3af;margin:0;">${t('c_stat3_label', 'Countries worldwide')}</p></div>
                <div><p style="font-size:3rem;font-weight:800;color:#fff;margin:0 0 4px;">24/7</p><p style="font-size:.9375rem;color:#9ca3af;margin:0;">${t('c_stat4_label', 'Customer support')}</p></div>
            </div>
        </section>`,
    });


    bm.add('gallery', {
        label: t('block_gallery', 'Photo gallery'),
        category: t('cat_media', 'Media'),
        media: faIcon('fa-images'),
        content: `
        <section style="padding:64px 24px;">
            <div style="max-width:1100px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 40px;">${t('c_gallery_heading', 'Gallery')}</h2>
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

    bm.add('gallery-slider', {
        label: t('block_gallery_slider', 'Gallery – Slider'),
        category: t('cat_media', 'Media'),
        media: faIcon('fa-film'),
        content: `
        <section style="padding:64px 24px;">
            <div style="max-width:1100px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 40px;">${t('c_gallery_heading', 'Gallery')}</h2>
                <div class="swiper" data-loop="true" data-per-view="auto" data-gap="16" data-centered="true" style="overflow:hidden;padding-bottom:44px;">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" style="width:380px;"><img src="https://placehold.co/760x500?text=1" data-gjs-type="media-image" style="width:100%;height:260px;object-fit:cover;border-radius:10px;display:block;" alt=""></div>
                        <div class="swiper-slide" style="width:380px;"><img src="https://placehold.co/760x500?text=2" data-gjs-type="media-image" style="width:100%;height:260px;object-fit:cover;border-radius:10px;display:block;" alt=""></div>
                        <div class="swiper-slide" style="width:380px;"><img src="https://placehold.co/760x500?text=3" data-gjs-type="media-image" style="width:100%;height:260px;object-fit:cover;border-radius:10px;display:block;" alt=""></div>
                        <div class="swiper-slide" style="width:380px;"><img src="https://placehold.co/760x500?text=4" data-gjs-type="media-image" style="width:100%;height:260px;object-fit:cover;border-radius:10px;display:block;" alt=""></div>
                        <div class="swiper-slide" style="width:380px;"><img src="https://placehold.co/760x500?text=5" data-gjs-type="media-image" style="width:100%;height:260px;object-fit:cover;border-radius:10px;display:block;" alt=""></div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </section>`,
    });

    bm.add('cards-slider', {
        label: t('block_cards_slider', 'Cards – Slider'),
        category: t('cat_media', 'Media'),
        media: faIcon('fa-table-cells-large'),
        content: `
        <section style="padding:64px 24px;background:#f9fafb;">
            <div style="max-width:1100px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 40px;">${t('c_cards_heading', 'Cards')}</h2>
                <div class="swiper" data-loop="true" data-gap="24" data-breakpoints='{"0":{"slidesPerView":1},"640":{"slidesPerView":2},"1024":{"slidesPerView":3}}' style="overflow:hidden;padding-bottom:44px;">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide"><div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);height:100%;"><div style="width:48px;height:48px;background:#eff6ff;border-radius:10px;margin:0 0 16px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-bolt" style="color:#2563eb;font-size:1.25rem;"></i></div><h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0 0 8px;">${t('c_cards_heading', 'Cards')} 1</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">${t('c_card_desc', 'Card description.')}</p></div></div>
                        <div class="swiper-slide"><div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);height:100%;"><div style="width:48px;height:48px;background:#f0fdf4;border-radius:10px;margin:0 0 16px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-shield-halved" style="color:#16a34a;font-size:1.25rem;"></i></div><h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0 0 8px;">${t('c_cards_heading', 'Cards')} 2</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">${t('c_card_desc', 'Card description.')}</p></div></div>
                        <div class="swiper-slide"><div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);height:100%;"><div style="width:48px;height:48px;background:#fdf4ff;border-radius:10px;margin:0 0 16px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-star" style="color:#9333ea;font-size:1.25rem;"></i></div><h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0 0 8px;">${t('c_cards_heading', 'Cards')} 3</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">${t('c_card_desc', 'Card description.')}</p></div></div>
                        <div class="swiper-slide"><div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);height:100%;"><div style="width:48px;height:48px;background:#fff7ed;border-radius:10px;margin:0 0 16px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-rocket" style="color:#ea580c;font-size:1.25rem;"></i></div><h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0 0 8px;">${t('c_cards_heading', 'Cards')} 4</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">${t('c_card_desc', 'Card description.')}</p></div></div>
                        <div class="swiper-slide"><div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);height:100%;"><div style="width:48px;height:48px;background:#fef2f2;border-radius:10px;margin:0 0 16px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-heart" style="color:#dc2626;font-size:1.25rem;"></i></div><h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0 0 8px;">${t('c_cards_heading', 'Cards')} 5</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">${t('c_card_desc', 'Card description.')}</p></div></div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </section>`,
    });

    bm.add('video-youtube', {
        label: t('block_youtube', 'YouTube video'),
        category: t('cat_media', 'Media'),
        media: faIcon('fa-brands fa-youtube'),
        content: `
        <section style="padding:64px 24px;background:#f9fafb;">
            <div style="max-width:800px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 32px;">${t('c_video_heading', 'Video')}</h2>
                <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:12px;">
                    <iframe data-gjs-type="youtube-video" src="https://www.youtube.com/embed/dQw4w9WgXcQ?rel=0" frameborder="0" allowfullscreen allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture" style="position:absolute;top:0;left:0;width:100%;height:100%;"></iframe>
                </div>
            </div>
        </section>`,
    });

    bm.add('video-embed', {
        label: t('block_video_mp4', 'Video (MP4)'),
        category: t('cat_media', 'Media'),
        media: faIcon('fa-clapperboard'),
        content: `
        <section style="padding:64px 24px;">
            <div style="max-width:800px;margin:0 auto;">
                <video data-gjs-type="mp4-video" controls preload="metadata" style="width:100%;border-radius:12px;display:block;"></video>
            </div>
        </section>`,
    });

    bm.add('map', {
        label: t('block_map', 'Map'),
        category: t('cat_media', 'Media'),
        media: faIcon('fa-map-location-dot'),
        content: `
        <section style="padding:64px 24px;">
            <div data-gjs-type="map-embed" style="max-width:1100px;margin:0 auto;border-radius:12px;overflow:hidden;height:420px;">
                <iframe src="https://maps.google.com/maps?q=Praha&output=embed&z=13" width="100%" height="100%" style="border:none;display:block;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </section>`,
    });


    bm.add('testimonials', {
        label: t('block_testimonials', 'Customer reviews'),
        category: t('cat_social_proof', 'Social Proof'),
        media: faIcon('fa-comments'),
        content: `
        <section style="padding:64px 24px;background:#f9fafb;">
            <div style="max-width:1100px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 12px;">${t('c_testimonials_heading', 'What customers say')}</h2>
                <p style="font-size:1rem;color:#6b7280;text-align:center;margin:0 0 40px;">${t('c_testimonials_subtext', 'Read experiences from people who trust us.')}</p>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px;">
                    <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                        <div style="display:flex;gap:2px;margin:0 0 16px;"><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i></div>
                        <p style="font-size:.9375rem;color:#374151;line-height:1.7;margin:0 0 20px;font-style:italic;">${t('c_testimonial1_text', '"Great product, I recommend it to everyone."')}</p>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="width:40px;height:40px;border-radius:50%;background:#e5e7eb;flex-shrink:0;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-user" style="color:#9ca3af;"></i></div>
                            <div><p style="font-size:.875rem;font-weight:600;color:#111827;margin:0;">${t('c_testimonial1_name', 'John Smith')}</p><p style="font-size:.8125rem;color:#9ca3af;margin:0;">${t('c_testimonial1_role', 'CEO, Company Ltd.')}</p></div>
                        </div>
                    </div>
                    <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                        <div style="display:flex;gap:2px;margin:0 0 16px;"><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i></div>
                        <p style="font-size:.9375rem;color:#374151;line-height:1.7;margin:0 0 20px;font-style:italic;">${t('c_testimonial2_text', '"Amazing support and easy to use."')}</p>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="width:40px;height:40px;border-radius:50%;background:#e5e7eb;flex-shrink:0;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-user" style="color:#9ca3af;"></i></div>
                            <div><p style="font-size:.875rem;font-weight:600;color:#111827;margin:0;">${t('c_testimonial2_name', 'Jane Doe')}</p><p style="font-size:.8125rem;color:#9ca3af;margin:0;">${t('c_testimonial2_role', 'Freelance designer')}</p></div>
                        </div>
                    </div>
                    <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                        <div style="display:flex;gap:2px;margin:0 0 16px;"><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i></div>
                        <p style="font-size:.9375rem;color:#374151;line-height:1.7;margin:0 0 20px;font-style:italic;">${t('c_testimonial3_text', '"Best investment of this year."')}</p>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="width:40px;height:40px;border-radius:50%;background:#e5e7eb;flex-shrink:0;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-user" style="color:#9ca3af;"></i></div>
                            <div><p style="font-size:.875rem;font-weight:600;color:#111827;margin:0;">${t('c_testimonial3_name', 'Mike Johnson')}</p><p style="font-size:.8125rem;color:#9ca3af;margin:0;">${t('c_testimonial3_role', 'Entrepreneur')}</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>`,
    });

    bm.add('testimonials-slider', {
        label: t('block_testimonials_slider', 'Reviews – Slider'),
        category: t('cat_social_proof', 'Social Proof'),
        media: faIcon('fa-quote-left'),
        content: `
        <section style="padding:64px 24px;background:#f9fafb;">
            <div style="max-width:760px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 40px;">${t('c_testimonials_heading', 'What customers say')}</h2>
                <div class="swiper" data-loop="true" data-autoplay="4000" style="overflow:hidden;padding-bottom:44px;">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide"><div style="background:#fff;border-radius:16px;padding:36px;box-shadow:0 1px 4px rgba(0,0,0,.06);text-align:center;"><div style="display:flex;gap:2px;justify-content:center;margin:0 0 20px;"><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i></div><p style="font-size:1.125rem;color:#374151;line-height:1.7;margin:0 0 28px;font-style:italic;">${t('c_testimonial1_text', '"Great product, I recommend it to everyone."')}</p><div style="display:flex;align-items:center;justify-content:center;gap:12px;"><div style="width:44px;height:44px;border-radius:50%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-user" style="color:#9ca3af;"></i></div><div style="text-align:left;"><p style="font-size:.875rem;font-weight:600;color:#111827;margin:0;">${t('c_testimonial1_name', 'John Smith')}</p><p style="font-size:.8125rem;color:#9ca3af;margin:0;">${t('c_testimonial1_role', 'CEO, Company Ltd.')}</p></div></div></div></div>
                        <div class="swiper-slide"><div style="background:#fff;border-radius:16px;padding:36px;box-shadow:0 1px 4px rgba(0,0,0,.06);text-align:center;"><div style="display:flex;gap:2px;justify-content:center;margin:0 0 20px;"><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i></div><p style="font-size:1.125rem;color:#374151;line-height:1.7;margin:0 0 28px;font-style:italic;">${t('c_testimonial2_text', '"Amazing support and easy to use."')}</p><div style="display:flex;align-items:center;justify-content:center;gap:12px;"><div style="width:44px;height:44px;border-radius:50%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-user" style="color:#9ca3af;"></i></div><div style="text-align:left;"><p style="font-size:.875rem;font-weight:600;color:#111827;margin:0;">${t('c_testimonial2_name', 'Jane Doe')}</p><p style="font-size:.8125rem;color:#9ca3af;margin:0;">${t('c_testimonial2_role', 'Freelance designer')}</p></div></div></div></div>
                        <div class="swiper-slide"><div style="background:#fff;border-radius:16px;padding:36px;box-shadow:0 1px 4px rgba(0,0,0,.06);text-align:center;"><div style="display:flex;gap:2px;justify-content:center;margin:0 0 20px;"><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i></div><p style="font-size:1.125rem;color:#374151;line-height:1.7;margin:0 0 28px;font-style:italic;">${t('c_testimonial3_text', '"Best investment of this year."')}</p><div style="display:flex;align-items:center;justify-content:center;gap:12px;"><div style="width:44px;height:44px;border-radius:50%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-user" style="color:#9ca3af;"></i></div><div style="text-align:left;"><p style="font-size:.875rem;font-weight:600;color:#111827;margin:0;">${t('c_testimonial3_name', 'Mike Johnson')}</p><p style="font-size:.8125rem;color:#9ca3af;margin:0;">${t('c_testimonial3_role', 'Entrepreneur')}</p></div></div></div></div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </section>`,
    });

    bm.add('logo-bar', {
        label: t('block_logo_bar', 'Partner logos'),
        category: t('cat_social_proof', 'Social Proof'),
        media: faIcon('fa-building'),
        content: `
        <section style="padding:40px 24px;border-top:1px solid #e5e7eb;border-bottom:1px solid #e5e7eb;">
            <div style="max-width:960px;margin:0 auto;">
                <p style="font-size:.875rem;font-weight:500;color:#9ca3af;text-align:center;margin:0 0 28px;text-transform:uppercase;letter-spacing:.08em;">${t('c_trusted_by', 'Trusted by')}</p>
                <div style="display:flex;align-items:center;justify-content:center;gap:48px;flex-wrap:wrap;">
                    <span style="font-size:1.375rem;font-weight:800;color:#d1d5db;letter-spacing:-.02em;">Firma A</span>
                    <span style="font-size:1.375rem;font-weight:800;color:#d1d5db;letter-spacing:-.02em;">Firma B</span>
                    <span style="font-size:1.375rem;font-weight:800;color:#d1d5db;letter-spacing:-.02em;">Firma C</span>
                    <span style="font-size:1.375rem;font-weight:800;color:#d1d5db;letter-spacing:-.02em;">Firma D</span>
                    <span style="font-size:1.375rem;font-weight:800;color:#d1d5db;letter-spacing:-.02em;">Firma E</span>
                </div>
            </div>
        </section>`,
    });

    bm.add('team', {
        label: t('block_team', 'Team'),
        category: t('cat_social_proof', 'Social Proof'),
        media: faIcon('fa-people-group'),
        content: `
        <section style="padding:64px 24px;">
            <div style="max-width:1000px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 12px;">${t('c_team_heading', 'Our team')}</h2>
                <p style="font-size:1rem;color:#6b7280;text-align:center;margin:0 0 40px;">${t('c_team_subtext', 'The people behind the project.')}</p>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:24px;">
                    <div style="text-align:center;"><img src="https://placehold.co/200x200?text=Foto" data-gjs-type="media-image" style="width:100px;height:100px;border-radius:50%;margin:0 auto 12px;display:block;object-fit:cover;" alt=""><p style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 4px;">${t('c_team1_name', 'John Smith')}</p><p style="font-size:.875rem;color:#6b7280;margin:0;">${t('c_team1_role', 'CEO & Founder')}</p></div>
                    <div style="text-align:center;"><img src="https://placehold.co/200x200?text=Foto" data-gjs-type="media-image" style="width:100px;height:100px;border-radius:50%;margin:0 auto 12px;display:block;object-fit:cover;" alt=""><p style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 4px;">${t('c_team2_name', 'Jane Doe')}</p><p style="font-size:.875rem;color:#6b7280;margin:0;">${t('c_team2_role', 'CTO')}</p></div>
                    <div style="text-align:center;"><img src="https://placehold.co/200x200?text=Foto" data-gjs-type="media-image" style="width:100px;height:100px;border-radius:50%;margin:0 auto 12px;display:block;object-fit:cover;" alt=""><p style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 4px;">${t('c_team3_name', 'Mike Johnson')}</p><p style="font-size:.875rem;color:#6b7280;margin:0;">${t('c_team3_role', 'Lead Developer')}</p></div>
                    <div style="text-align:center;"><img src="https://placehold.co/200x200?text=Foto" data-gjs-type="media-image" style="width:100px;height:100px;border-radius:50%;margin:0 auto 12px;display:block;object-fit:cover;" alt=""><p style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 4px;">${t('c_team4_name', 'Sarah Brown')}</p><p style="font-size:.875rem;color:#6b7280;margin:0;">${t('c_team4_role', 'Head of Design')}</p></div>
                </div>
            </div>
        </section>`,
    });


    bm.add('cta', {
        label: t('block_cta', 'Call to action (CTA)'),
        category: t('cat_conversion', 'Conversion'),
        media: faIcon('fa-bullhorn'),
        content: `
        <section style="padding:80px 24px;background:#111827;text-align:center;">
            <div style="max-width:600px;margin:0 auto;">
                <h2 style="font-size:2.25rem;font-weight:800;color:#fff;margin:0 0 16px;">${t('c_cta_heading', 'Ready to get started?')}</h2>
                <p style="font-size:1.125rem;color:#9ca3af;margin:0 0 32px;line-height:1.7;">${t('c_cta_subtext', 'Join thousands of satisfied customers today.')}</p>
                <div style="display:flex;justify-content:center;gap:12px;flex-wrap:wrap;">
                    <a href="#" data-gjs-type="button-link" style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;font-size:.9375rem;font-weight:600;border-radius:8px;text-decoration:none;cursor:pointer;background:#fff;color:#111827;border:2px solid #fff;">${t('c_btn_start_free', 'Start for free')}</a>
                    <a href="#" data-gjs-type="button-link" style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;font-size:.9375rem;font-weight:600;border-radius:8px;text-decoration:none;cursor:pointer;background:transparent;color:#fff;border:2px solid #4b5563;">${t('c_btn_contact', 'Contact us')}</a>
                </div>
            </div>
        </section>`,
    });

    bm.add('pricing', {
        label: t('block_pricing', 'Pricing'),
        category: t('cat_conversion', 'Conversion'),
        media: faIcon('fa-tag'),
        content: `
        <section style="padding:64px 24px;background:#f9fafb;">
            <div style="max-width:1000px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 12px;">${t('c_pricing_heading', 'Simple pricing')}</h2>
                <p style="font-size:1rem;color:#6b7280;text-align:center;margin:0 0 48px;">${t('c_pricing_subtext', 'Choose the plan that suits you. No hidden fees.')}</p>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px;">
                    <div style="background:#fff;border-radius:16px;padding:32px;border:1px solid #e5e7eb;">
                        <h3 style="font-size:.875rem;font-weight:600;color:#6b7280;margin:0 0 12px;text-transform:uppercase;letter-spacing:.05em;">Starter</h3>
                        <p style="font-size:2.5rem;font-weight:800;color:#111827;margin:0 0 4px;">0 Kč</p>
                        <p style="font-size:.875rem;color:#9ca3af;margin:0 0 24px;">${t('c_per_month', '/ month')}</p>
                        <ul style="list-style:none;padding:0;margin:0 0 28px;display:flex;flex-direction:column;gap:10px;">
                            <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;"><i class="fa-solid fa-check" style="color:#16a34a;"></i> ${t('c_feat_1web', '1 website')}</li>
                            <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;"><i class="fa-solid fa-check" style="color:#16a34a;"></i> ${t('c_feat_5pages', '5 pages')}</li>
                            <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;color:#9ca3af;"><i class="fa-solid fa-xmark" style="color:#d1d5db;"></i> ${t('c_feat_own_domain', 'Custom domain')}</li>
                        </ul>
                        <a href="#" style="display:block;text-align:center;padding:12px;background:#f9fafb;color:#111827;font-weight:600;border-radius:8px;text-decoration:none;border:1px solid #e5e7eb;">${t('c_btn_start_free', 'Start for free')}</a>
                    </div>
                    <div style="background:#111827;border-radius:16px;padding:32px;">
                        <h3 style="font-size:.875rem;font-weight:600;color:#9ca3af;margin:0 0 12px;text-transform:uppercase;letter-spacing:.05em;">Pro</h3>
                        <p style="font-size:2.5rem;font-weight:800;color:#fff;margin:0 0 4px;">490 Kč</p>
                        <p style="font-size:.875rem;color:#6b7280;margin:0 0 24px;">${t('c_per_month', '/ month')}</p>
                        <ul style="list-style:none;padding:0;margin:0 0 28px;display:flex;flex-direction:column;gap:10px;">
                            <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;color:#e5e7eb;"><i class="fa-solid fa-check" style="color:#4ade80;"></i> ${t('c_feat_5webs', '5 websites')}</li>
                            <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;color:#e5e7eb;"><i class="fa-solid fa-check" style="color:#4ade80;"></i> ${t('c_feat_unl_pages', 'Unlimited pages')}</li>
                            <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;color:#e5e7eb;"><i class="fa-solid fa-check" style="color:#4ade80;"></i> ${t('c_feat_own_domain', 'Custom domain')}</li>
                        </ul>
                        <a href="#" style="display:block;text-align:center;padding:12px;background:#2563eb;color:#fff;font-weight:600;border-radius:8px;text-decoration:none;">${t('c_btn_choose_pro', 'Choose Pro')}</a>
                    </div>
                    <div style="background:#fff;border-radius:16px;padding:32px;border:1px solid #e5e7eb;">
                        <h3 style="font-size:.875rem;font-weight:600;color:#6b7280;margin:0 0 12px;text-transform:uppercase;letter-spacing:.05em;">Enterprise</h3>
                        <p style="font-size:2.5rem;font-weight:800;color:#111827;margin:0 0 4px;">${t('c_price_custom', 'Custom')}</p>
                        <p style="font-size:.875rem;color:#9ca3af;margin:0 0 24px;">${t('c_by_agreement', 'by agreement')}</p>
                        <ul style="list-style:none;padding:0;margin:0 0 28px;display:flex;flex-direction:column;gap:10px;">
                            <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;"><i class="fa-solid fa-check" style="color:#16a34a;"></i> ${t('c_feat_unl_webs', 'Unlimited websites')}</li>
                            <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;"><i class="fa-solid fa-check" style="color:#16a34a;"></i> ${t('c_feat_sla', 'SLA support')}</li>
                            <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;"><i class="fa-solid fa-check" style="color:#16a34a;"></i> ${t('c_feat_custom_int', 'Custom integrations')}</li>
                        </ul>
                        <a href="#" style="display:block;text-align:center;padding:12px;background:#f9fafb;color:#111827;font-weight:600;border-radius:8px;text-decoration:none;border:1px solid #e5e7eb;">${t('c_btn_contact', 'Contact us')}</a>
                    </div>
                </div>
            </div>
        </section>`,
    });

    bm.add('contact-form', {
        label: t('block_contact', 'Contact form'),
        category: t('cat_conversion', 'Conversion'),
        media: faIcon('fa-envelope'),
        content: `
        <section style="padding:64px 24px;background:#f9fafb;">
            <div style="max-width:560px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 8px;">${t('c_contact_heading', 'Contact us')}</h2>
                <p style="font-size:1rem;color:#6b7280;text-align:center;margin:0 0 36px;">${t('c_contact_subtext', 'We respond within 24 hours on business days.')}</p>
                <form data-contact-form style="display:flex;flex-direction:column;gap:16px;">
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
                        <div><label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin:0 0 6px;">${t('c_form_name', 'Name')}</label><input name="name" type="text" placeholder="${t('c_form_name_ph', 'John Smith')}" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:.9375rem;box-sizing:border-box;" required></div>
                        <div><label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin:0 0 6px;">${t('c_form_email', 'E-mail')}</label><input name="email" type="email" placeholder="${t('c_form_email_ph', 'john@example.com')}" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:.9375rem;box-sizing:border-box;" required></div>
                    </div>
                    <div><label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin:0 0 6px;">${t('c_form_subject', 'Subject')}</label><input name="subject" type="text" placeholder="${t('c_form_subject_ph', 'Question about...')}" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:.9375rem;box-sizing:border-box;"></div>
                    <div><label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin:0 0 6px;">${t('c_form_message', 'Message')}</label><textarea name="message" rows="5" placeholder="${t('c_form_message_ph', 'Your message...')}" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:.9375rem;resize:vertical;box-sizing:border-box;" required></textarea></div>
                    <div id="form-status" style="display:none;padding:12px;border-radius:8px;font-size:.9375rem;text-align:center;"></div>
                    <button type="submit" style="padding:14px;background:#111827;color:#fff;font-weight:600;border:none;border-radius:8px;font-size:1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;"><i class="fa-solid fa-paper-plane"></i> ${t('c_btn_send', 'Send message')}</button>
                </form>
            </div>
        </section>`,
    });

    bm.add('newsletter', {
        label: t('block_newsletter', 'Newsletter'),
        category: t('cat_conversion', 'Conversion'),
        media: faIcon('fa-at'),
        content: `
        <section style="padding:64px 24px;background:#eff6ff;">
            <div style="max-width:560px;margin:0 auto;text-align:center;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;margin:0 0 12px;">${t('c_newsletter_heading', 'Stay informed')}</h2>
                <p style="font-size:1rem;color:#6b7280;margin:0 0 32px;line-height:1.7;">${t('c_newsletter_subtext', 'News and tips straight to your email. No spam, unsubscribe anytime.')}</p>
                <form data-contact-form style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
                    <input name="email" type="email" placeholder="${t('c_email_ph', 'your@email.com')}" required style="flex:1;min-width:220px;padding:12px 16px;border:1px solid #bfdbfe;border-radius:8px;font-size:.9375rem;background:#fff;box-sizing:border-box;">
                    <button type="submit" style="padding:12px 24px;background:#2563eb;color:#fff;font-weight:600;border:none;border-radius:8px;font-size:.9375rem;cursor:pointer;white-space:nowrap;">${t('c_btn_subscribe', 'Subscribe')}</button>
                </form>
            </div>
        </section>`,
    });


    bm.add('faq', {
        label: t('block_faq', 'FAQ'),
        category: t('cat_info', 'Info & Help'),
        media: faIcon('fa-circle-question'),
        content: `
        <section style="padding:64px 24px;">
            <div style="max-width:720px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 12px;">${t('c_faq_heading', 'Frequently asked questions')}</h2>
                <p style="font-size:1rem;color:#6b7280;text-align:center;margin:0 0 40px;">${t('c_faq_subtext', 'Answers to the most common customer questions.')}</p>
                <div style="display:flex;flex-direction:column;">
                    <div style="border-bottom:1px solid #e5e7eb;padding:20px 0;"><h3 style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 8px;">${t('c_faq_q1', 'How can I get started?')}</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.7;">${t('c_faq_a1', 'Just register and start immediately. No credit card required.')}</p></div>
                    <div style="border-bottom:1px solid #e5e7eb;padding:20px 0;"><h3 style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 8px;">${t('c_faq_q2', 'Is there a free version?')}</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.7;">${t('c_faq_a2', 'Yes, we offer a free plan with no time limit.')}</p></div>
                    <div style="border-bottom:1px solid #e5e7eb;padding:20px 0;"><h3 style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 8px;">${t('c_faq_q3', 'How does customer support work?')}</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.7;">${t('c_faq_a3', 'Email support is available on business days 9am–5pm.')}</p></div>
                    <div style="padding:20px 0;"><h3 style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 8px;">${t('c_faq_q4', 'Can I cancel my subscription at any time?')}</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.7;">${t('c_faq_a4', 'Yes, without contractual obligations or hidden fees.')}</p></div>
                </div>
            </div>
        </section>`,
    });

    bm.add('accordion', {
        label: t('block_accordion', 'Accordion (interactive)'),
        category: t('cat_info', 'Info & Help'),
        media: faIcon('fa-bars-staggered'),
        content: `
        <section style="padding:64px 24px;">
            <div style="max-width:720px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 40px;">${t('c_faq_heading', 'Frequently asked questions')}</h2>
                <details style="border-bottom:1px solid #e5e7eb;">
                    <summary style="padding:20px 0;font-size:1rem;font-weight:600;color:#111827;cursor:pointer;display:flex;justify-content:space-between;align-items:center;list-style:none;-webkit-appearance:none;"><span>${t('c_faq_q1', 'How can I get started?')}</span><i class="fa-solid fa-chevron-down faq-chevron"></i></summary>
                    <p style="font-size:.9375rem;color:#6b7280;padding:0 0 20px;margin:0;line-height:1.7;">${t('c_faq_a1', 'Just register and start immediately. No credit card required.')}</p>
                </details>
                <details style="border-bottom:1px solid #e5e7eb;">
                    <summary style="padding:20px 0;font-size:1rem;font-weight:600;color:#111827;cursor:pointer;display:flex;justify-content:space-between;align-items:center;list-style:none;-webkit-appearance:none;"><span>${t('c_faq_q2', 'Is there a free version?')}</span><i class="fa-solid fa-chevron-down faq-chevron"></i></summary>
                    <p style="font-size:.9375rem;color:#6b7280;padding:0 0 20px;margin:0;line-height:1.7;">${t('c_faq_a2', 'Yes, we offer a free plan with no time limit.')}</p>
                </details>
                <details style="border-bottom:1px solid #e5e7eb;">
                    <summary style="padding:20px 0;font-size:1rem;font-weight:600;color:#111827;cursor:pointer;display:flex;justify-content:space-between;align-items:center;list-style:none;-webkit-appearance:none;"><span>${t('c_faq_q3', 'How does customer support work?')}</span><i class="fa-solid fa-chevron-down faq-chevron"></i></summary>
                    <p style="font-size:.9375rem;color:#6b7280;padding:0 0 20px;margin:0;line-height:1.7;">${t('c_faq_a3', 'Email support is available on business days 9am–5pm.')}</p>
                </details>
                <details>
                    <summary style="padding:20px 0;font-size:1rem;font-weight:600;color:#111827;cursor:pointer;display:flex;justify-content:space-between;align-items:center;list-style:none;-webkit-appearance:none;"><span>${t('c_faq_q4', 'Can I cancel my subscription at any time?')}</span><i class="fa-solid fa-chevron-down faq-chevron"></i></summary>
                    <p style="font-size:.9375rem;color:#6b7280;padding:0 0 20px;margin:0;line-height:1.7;">${t('c_faq_a4', 'Yes, without contractual obligations or hidden fees.')}</p>
                </details>
            </div>
        </section>`,
    });

    bm.add('divider', {
        label: t('block_divider', 'Divider'),
        category: t('cat_info', 'Info & Help'),
        media: faIcon('fa-minus'),
        content: '<div style="padding:32px 24px;"><hr style="border:none;border-top:1px solid #e5e7eb;"></div>',
    });


    bm.add('button-primary', {
        label: t('block_button', 'Button'),
        category: t('cat_elements', 'Elements'),
        media: faIcon('fa-hand-pointer'),
        content: `<a href="#" data-gjs-type="button-link" style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;font-size:.9375rem;font-weight:600;border-radius:8px;text-decoration:none;cursor:pointer;background:#111827;color:#fff;border:2px solid #111827;">${t('c_btn_text', 'Button')}</a>`,
    });

    bm.add('image-block', {
        label: t('block_image_full', 'Image (full width)'),
        category: t('cat_elements', 'Elements'),
        media: faIcon('fa-image'),
        content: '<img src="https://placehold.co/800x400?text=Foto" data-gjs-type="media-image" style="width:100%;height:auto;display:block;border-radius:8px;" alt="">',
    });

    bm.add('image-centered', {
        label: t('block_image_centered', 'Image (centered)'),
        category: t('cat_elements', 'Elements'),
        media: faIcon('fa-expand'),
        content: '<div style="display:flex;justify-content:center;"><img src="https://placehold.co/400x300?text=Foto" data-gjs-type="media-image" style="width:400px;max-width:100%;height:auto;display:block;border-radius:8px;" alt=""></div>',
    });

    bm.add('icon-block', {
        label: t('block_icon', 'FA Icon'),
        category: t('cat_elements', 'Elements'),
        media: faIcon('fa-icons'),
        content: '<i class="fa-solid fa-star" style="font-size:2rem;color:#111827;display:block;"></i>',
    });

    bm.add('badge', {
        label: t('block_badge', 'Badge'),
        category: t('cat_elements', 'Elements'),
        media: faIcon('fa-certificate'),
        content: `<span style="display:inline-block;padding:4px 12px;background:#eff6ff;color:#2563eb;font-size:.8125rem;font-weight:600;border-radius:999px;">${t('c_new_badge', 'New')}</span>`,
    });

    bm.add('link-text', {
        label: t('block_link', 'Text link'),
        category: t('cat_elements', 'Elements'),
        media: faIcon('fa-link'),
        content: `<a href="#" style="color:#2563eb;text-decoration:underline;font-size:1rem;cursor:pointer;">${t('c_link_text', 'Click here')}</a>`,
    });

    bm.add('spacer', {
        label: t('block_spacer', 'Spacer'),
        category: t('cat_elements', 'Elements'),
        media: faIcon('fa-up-down'),
        content: '<div style="height:80px;"></div>',
    });

    bm.add('table', {
        label: t('block_table', 'Table'),
        category: t('cat_elements', 'Elements'),
        media: faIcon('fa-table'),
        content: `
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:.9375rem;">
                <thead>
                    <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb;">
                        <th style="padding:12px 16px;text-align:left;font-weight:600;color:#374151;">${t('c_table_col', 'Column')} 1</th>
                        <th style="padding:12px 16px;text-align:left;font-weight:600;color:#374151;">${t('c_table_col', 'Column')} 2</th>
                        <th style="padding:12px 16px;text-align:left;font-weight:600;color:#374151;">${t('c_table_col', 'Column')} 3</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom:1px solid #e5e7eb;">
                        <td style="padding:12px 16px;color:#374151;">${t('c_table_row', 'Row')} 1</td>
                        <td style="padding:12px 16px;color:#374151;">${t('c_table_data', 'Data')}</td>
                        <td style="padding:12px 16px;color:#374151;">${t('c_table_data', 'Data')}</td>
                    </tr>
                    <tr style="border-bottom:1px solid #e5e7eb;">
                        <td style="padding:12px 16px;color:#374151;">${t('c_table_row', 'Row')} 2</td>
                        <td style="padding:12px 16px;color:#374151;">${t('c_table_data', 'Data')}</td>
                        <td style="padding:12px 16px;color:#374151;">${t('c_table_data', 'Data')}</td>
                    </tr>
                    <tr>
                        <td style="padding:12px 16px;color:#374151;">${t('c_table_row', 'Row')} 3</td>
                        <td style="padding:12px 16px;color:#374151;">${t('c_table_data', 'Data')}</td>
                        <td style="padding:12px 16px;color:#374151;">${t('c_table_data', 'Data')}</td>
                    </tr>
                </tbody>
            </table>
        </div>`,
    });

    bm.add('html-embed-block', {
        label: t('block_html', 'HTML code'),
        category: t('cat_elements', 'Elements'),
        media: faIcon('fa-code'),
        content: '<div data-gjs-type="html-embed" style="padding:16px;border:2px dashed #e5e7eb;border-radius:8px;text-align:center;color:#9ca3af;font-size:.875rem;"><!-- HTML --></div>',
    });
}