import { t } from './trans';

export function registerComponents(editor) {
    editor.Components.addType('button-link', {
        isComponent: (el) => el.dataset?.gjsType === 'button-link',
        extend: 'text',
        model: {
            defaults: {
                tagName: 'a',
                name: 'Button',
                draggable: true,
                droppable: false,
                btnIcon: '',
                resizable: {
                    handles: ['cl', 'cr'],
                    currentUnit: 1,
                    unitWidth: 'px',
                    minDim: 40,
                    step: 1,
                },
                traits: [
                    {
                        type: 'text',
                        name: 'href',
                        label: t('trait_btn_href', 'URL'),
                        placeholder: 'https://',
                    },
                    {
                        type: 'select',
                        name: 'target',
                        label: t('trait_btn_target', 'Open in'),
                        options: [
                            { value: '_self',  name: t('trait_target_self', 'Same window') },
                            { value: '_blank', name: t('trait_target_blank', 'New window') },
                        ],
                    },
                    {
                        type: 'text',
                        name: 'btnIcon',
                        label: t('trait_btn_icon', 'Icon (arrow-right, star, check…)'),
                        placeholder: 'arrow-right',
                        changeProp: true,
                    },
                ],
            },
            init() {
                this.on('change:btnIcon', this.syncIcon);
            },
            syncIcon() {
                const raw  = (this.get('btnIcon') || '').trim();
                const comps = this.components();
                const existing = comps.models.find(c => c.getAttributes()['data-btn-icon'] === '1');

                if (!raw) {
                    existing?.remove();
                    return;
                }

                // Accept "arrow-right", "fa-arrow-right" or "fa-solid fa-arrow-right"
                const name = raw
                    .replace(/^fa-solid\s+fa-/i, '')
                    .replace(/^fa-solid\s+/i, '')
                    .replace(/^fa-/i, '');
                const cls = `fa-solid fa-${name}`;

                if (existing) {
                    existing.addAttributes({ class: cls });
                } else {
                    comps.add(
                        {
                            tagName: 'i',
                            attributes: { class: cls, 'data-btn-icon': '1', 'aria-hidden': 'true' },
                            selectable: false,
                            hoverable: false,
                            layerable: false,
                            copyable: false,
                            removable: false,
                        },
                        { at: 0 },
                    );
                }
            },
        },
    });

    editor.Components.addType('media-image', {
        isComponent: (el) => el.tagName === 'IMG' && el.dataset?.gjsType === 'media-image',
        extend: 'image',
        model: {
            defaults: {
                traits: [
                    { type: 'text',   name: 'src', label: t('trait_img_src', 'Image URL') },
                    { type: 'text',   name: 'alt', label: t('trait_img_alt', 'Alt text (SEO)') },
                    { type: 'text',   name: 'title', label: t('trait_img_title', 'Title') },
                    {
                        type: 'select', name: 'object-fit', label: t('trait_img_fit', 'Object fit'),
                        options: [
                            { value: 'cover',   name: 'Cover' },
                            { value: 'contain', name: 'Contain' },
                            { value: 'fill',    name: 'Fill' },
                            { value: 'none',    name: 'None' },
                        ],
                    },
                ],
            },
        },
    });

    editor.Components.addType('youtube-video', {
        isComponent: (el) => el.dataset?.gjsType === 'youtube-video',
        model: {
            defaults: {
                tagName: 'div',
                name: 'YouTube Video',
                droppable: false,
                resizable: { handles: ['cl', 'cr', 'bc', 'tc'], currentUnit: 1, unitWidth: 'px', unitHeight: 'px', minDim: 120, step: 1 },
                ytUrl: '',
                ytAutoplay: false,
                ytControls: true,
                ytRel: false,
                traits: [
                    {
                        type: 'text',
                        name: 'ytUrl',
                        label: t('trait_yt_url', 'YouTube URL or video ID'),
                        placeholder: 'https://www.youtube.com/watch?v=...',
                        changeProp: true,
                    },
                    { type: 'checkbox', name: 'ytAutoplay', label: t('trait_yt_autoplay', 'Autoplay'),          changeProp: true },
                    { type: 'checkbox', name: 'ytControls', label: t('trait_yt_controls', 'Show controls'),     changeProp: true },
                    { type: 'checkbox', name: 'ytRel',      label: t('trait_yt_rel', 'Related videos at end'),  changeProp: true },
                ],
            },
            init() {
                if (!this.get('ytUrl')) {
                    const iframeModel = this.components().filter(c => c.get('tagName') === 'iframe')[0];
                    const src = iframeModel?.getAttributes()?.src || '';
                    const m = src.match(/embed\/([a-zA-Z0-9_-]{11})/);
                    if (m) this.set('ytUrl', `https://www.youtube.com/watch?v=${m[1]}`, { silent: true });
                }
                this.on('change:ytUrl change:ytAutoplay change:ytControls change:ytRel', this.syncSrc);
            },
            syncSrc() {
                const url = (this.get('ytUrl') || '').trim();
                const iframe = this.view?.el?.querySelector('iframe');

                if (!url) {
                    if (iframe) iframe.src = '';
                    return;
                }

                const match = url.match(/(?:youtu\.be\/|[?&]v=|embed\/)([a-zA-Z0-9_-]{11})/);
                const id = match ? match[1] : (/^[a-zA-Z0-9_-]{11}$/.test(url) ? url : null);
                if (!id) return;

                const params = [`rel=${this.get('ytRel') ? '1' : '0'}`];
                if (this.get('ytAutoplay')) params.push('autoplay=1', 'mute=1');
                if (!this.get('ytControls')) params.push('controls=0');

                const src = `https://www.youtube.com/embed/${id}?${params.join('&')}`;

                // Update DOM immediately
                if (iframe) iframe.src = src;

                // Update child component model so src is saved in project data
                this.components().each(c => {
                    if (c.get('tagName') === 'iframe') c.addAttributes({ src });
                });
            },
        },
        view: {
            onRender() {
                this.el.setAttribute('data-gjs-type', 'youtube-video');
                addIframeOverlay(this.el);
                if (this.model.get('ytUrl')) this.model.syncSrc();
            },
        },
    });

    editor.Components.addType('mp4-video', {
        isComponent: (el) => el.tagName === 'VIDEO' && el.dataset?.gjsType === 'mp4-video',
        model: {
            defaults: {
                tagName: 'video',
                name: 'MP4 Video',
                droppable: false,
                videoSrc: '',
                videoControls: true,
                videoAutoplay: false,
                videoMuted: false,
                videoLoop: false,
                traits: [
                    {
                        type: 'media-url',
                        name: 'videoSrc',
                        label: t('trait_video_src', 'Video source'),
                        placeholder: 'https://example.com/video.mp4',
                        accept: 'video',
                        changeProp: true,
                    },
                    { type: 'checkbox', name: 'videoControls', label: t('trait_video_controls', 'Controls'), changeProp: true },
                    { type: 'checkbox', name: 'videoAutoplay', label: t('trait_video_autoplay', 'Autoplay'), changeProp: true },
                    { type: 'checkbox', name: 'videoMuted',    label: t('trait_video_muted', 'Mute'),       changeProp: true },
                    { type: 'checkbox', name: 'videoLoop',     label: t('trait_video_loop', 'Loop'),        changeProp: true },
                ],
            },
            init() {
                this.on('change:videoSrc change:videoControls change:videoAutoplay change:videoMuted change:videoLoop', this.syncAttrs);
            },
            syncAttrs() {
                const src = this.get('videoSrc') || '';
                const toAdd = { 'data-gjs-type': 'mp4-video', preload: 'metadata' };
                const toRemove = [];

                if (src) { toAdd.src = src; } else { toRemove.push('src'); }
                if (this.get('videoControls')) { toAdd.controls = 'controls'; } else { toRemove.push('controls'); }
                if (this.get('videoAutoplay')) { toAdd.autoplay = 'autoplay'; } else { toRemove.push('autoplay'); }
                if (this.get('videoMuted'))    { toAdd.muted    = 'muted';    } else { toRemove.push('muted'); }
                if (this.get('videoLoop'))     { toAdd.loop     = 'loop';     } else { toRemove.push('loop'); }

                this.addAttributes(toAdd);
                if (toRemove.length) this.removeAttributes(toRemove);
            },
        },
        view: {
            onRender() {
                this.el.setAttribute('data-gjs-type', 'mp4-video');
                addIframeOverlay(this.el);
            },
        },
    });

    editor.Components.addType('map-embed', {
        isComponent: (el) => el.dataset?.gjsType === 'map-embed',
        model: {
            defaults: {
                tagName: 'div',
                name: 'Map',
                droppable: false,
                mapAddress: '',
                mapZoom: '15',
                traits: [
                    {
                        type: 'text',
                        name: 'mapAddress',
                        label: t('trait_map_address', 'Address / place name'),
                        placeholder: t('trait_map_address_placeholder', 'Prague, Wenceslas Square 1'),
                        changeProp: true,
                    },
                    {
                        type: 'select',
                        name: 'mapZoom',
                        label: t('trait_map_zoom', 'Zoom'),
                        changeProp: true,
                        options: [
                            { value: '10', name: t('trait_map_zoom_city', 'City') },
                            { value: '13', name: t('trait_map_zoom_district', 'District') },
                            { value: '15', name: t('trait_map_zoom_street', 'Street (default)') },
                            { value: '17', name: t('trait_map_zoom_building', 'Building') },
                            { value: '19', name: t('trait_map_zoom_detail', 'Detail') },
                        ],
                    },
                ],
            },
            init() {
                this.on('change:mapAddress change:mapZoom', this.syncMap);
            },
            syncMap() {
                const address = this.get('mapAddress');
                if (!address) return;
                const src = `https://maps.google.com/maps?q=${encodeURIComponent(address)}&output=embed&z=${this.get('mapZoom') || 15}`;
                this.components().each(c => {
                    if (c.get('tagName') === 'iframe') {
                        c.addAttributes({ src });
                    }
                });
            },
        },
        view: {
            onRender() {
                this.el.setAttribute('data-gjs-type', 'map-embed');
                addIframeOverlay(this.el);
            },
        },
    });

    editor.Components.addType('html-embed', {
        isComponent: (el) => el.dataset?.gjsType === 'html-embed',
        model: {
            defaults: {
                tagName: 'div',
                name: 'HTML Embed',
                droppable: false,
                traits: [
                    { type: 'textarea', name: 'embedCode', label: t('trait_embed_code', 'HTML code'), changeProp: true },
                ],
            },
            init() {
                this.on('change:embedCode', () => {
                    this.components(this.get('embedCode') || '');
                });
            },
        },
        view: {
            onRender() { this.el.setAttribute('data-gjs-type', 'html-embed'); },
        },
    });

    const resizableDefaults = {
        handles: ['cl', 'cr', 'bc'],
        currentUnit: 1,
        unitWidth: 'px',
        unitHeight: 'px',
        minDim: 10,
        step: 1,
    };

    editor.DomComponents.addType('default', {
        model: { defaults: { resizable: resizableDefaults } },
    });

    editor.DomComponents.addType('text', {
        model: { defaults: { resizable: { ...resizableDefaults, handles: ['cl', 'cr'] } } },
    });

    editor.DomComponents.addType('image', {
        model: { defaults: { resizable: { ...resizableDefaults, handles: ['tl', 'tr', 'bl', 'br', 'cl', 'cr', 'bc'] } } },
    });
}

function addIframeOverlay(el) {
    if (el.querySelector('.gjs-iframe-overlay')) return;
    const overlay = document.createElement('div');
    overlay.className = 'gjs-iframe-overlay';
    overlay.style.cssText = 'position:absolute;inset:0;z-index:9;cursor:pointer;';
    el.appendChild(overlay);
}