import { t } from './trans';

export function registerComponents(editor) {
    editor.Components.addType('button-link', {
        isComponent: (el) => el.dataset?.gjsType === 'button-link',
        model: {
            defaults: {
                tagName: 'a',
                draggable: true,
                droppable: false,
                traits: [
                    { type: 'text', name: 'content', label: t('trait_btn_content', 'Button text'), changeProp: true },
                    {
                        type: 'select', name: 'variant', label: t('trait_btn_variant', 'Style'), changeProp: true,
                        options: [
                            { value: 'primary', name: 'Primary' },
                            { value: 'outline', name: 'Outline' },
                            { value: 'ghost',   name: 'Ghost' },
                            { value: 'white',   name: t('trait_btn_white', 'White') },
                        ],
                    },
                    {
                        type: 'select', name: 'size', label: t('trait_btn_size', 'Size'), changeProp: true,
                        options: [
                            { value: 'sm', name: t('trait_btn_sm', 'Small') },
                            { value: 'md', name: t('trait_btn_md', 'Medium') },
                            { value: 'lg', name: t('trait_btn_lg', 'Large') },
                            { value: 'xl', name: t('trait_btn_xl', 'Extra large') },
                        ],
                    },
                    { type: 'text', name: 'href',   label: t('trait_btn_href', 'URL') },
                    {
                        type: 'select', name: 'target', label: t('trait_btn_target', 'Open'),
                        options: [
                            { value: '_self',  name: t('trait_target_self', 'Same window') },
                            { value: '_blank', name: t('trait_target_blank', 'New window') },
                        ],
                    },
                    { type: 'text', name: 'icon', label: t('trait_btn_icon', 'FA icon (fa-arrow-right)') },
                ],
            },
            init() {
                this.on('change:variant change:size change:content change:icon', this.updateStyles);
            },
            updateStyles() {
                const variant  = this.get('variant') || 'primary';
                const size     = this.get('size')    || 'md';
                const content  = this.get('content') || t('trait_btn_content', 'Button');
                const icon     = this.get('icon');
                const padding  = { sm: '8px 18px', md: '12px 28px', lg: '16px 36px', xl: '18px 44px' }[size] || '12px 28px';
                const fontSize = { sm: '0.8125rem', md: '0.9375rem', lg: '1rem', xl: '1.125rem' }[size] || '0.9375rem';
                const styles   = {
                    primary: 'background:#111827;color:#fff;border:2px solid #111827;',
                    outline: 'background:transparent;color:#111827;border:2px solid #111827;',
                    ghost:   'background:transparent;color:#111827;border:2px solid transparent;',
                    white:   'background:#fff;color:#111827;border:2px solid #fff;',
                }[variant] || '';
                this.addStyle(`display:inline-flex;align-items:center;gap:8px;padding:${padding};font-size:${fontSize};font-weight:600;border-radius:8px;text-decoration:none;cursor:pointer;transition:opacity .15s;${styles}`);
                this.components(`${icon ? `<i class="fa-solid ${icon}"></i> ` : ''}${content}`);
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
        isComponent: (el) => el.tagName === 'IFRAME' && el.dataset?.gjsType === 'youtube-video',
        model: {
            defaults: {
                tagName: 'iframe',
                name: 'YouTube Video',
                droppable: false,
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
                    { type: 'checkbox', name: 'ytAutoplay',  label: t('trait_yt_autoplay', 'Autoplay'),         changeProp: true },
                    { type: 'checkbox', name: 'ytControls',  label: t('trait_yt_controls', 'Show controls'),    changeProp: true },
                    { type: 'checkbox', name: 'ytRel',       label: t('trait_yt_rel', 'Related videos at end'), changeProp: true },
                ],
            },
            init() {
                this.on('change:ytUrl change:ytAutoplay change:ytControls change:ytRel', this.syncSrc);
            },
            syncSrc() {
                const url   = this.get('ytUrl') || '';
                const match = url.match(/(?:youtu\.be\/|[?&]v=)([a-zA-Z0-9_-]{11})/);
                const id    = match ? match[1] : url.replace(/\s/g, '');
                if (!id) return;
                const params = [`rel=${this.get('ytRel') ? '1' : '0'}`];
                if (this.get('ytAutoplay')) params.push('autoplay=1');
                if (!this.get('ytControls')) params.push('controls=0');
                this.addAttributes({
                    src: `https://www.youtube.com/embed/${id}?${params.join('&')}`,
                    frameborder: '0',
                    allowfullscreen: 'true',
                    allow: 'accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture',
                    'data-gjs-type': 'youtube-video',
                });
            },
        },
        view: {
            onRender() { this.el.setAttribute('data-gjs-type', 'youtube-video'); },
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
            onRender() { this.el.setAttribute('data-gjs-type', 'mp4-video'); },
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
            onRender() { this.el.setAttribute('data-gjs-type', 'map-embed'); },
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

    editor.DomComponents.addType('default', {
        model: {
            defaults: {
                resizable: {
                    handles: ['tl', 'tc', 'tr', 'cl', 'cr', 'bl', 'bc', 'br'],
                    currentUnit: 1,
                    unitWidth: 'px',
                    unitHeight: 'px',
                    minDim: 10,
                    step: 1,
                },
            },
        },
    });
}