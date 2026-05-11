export function registerComponents(editor) {
    editor.Components.addType('button-link', {
        isComponent: (el) => el.dataset?.gjsType === 'button-link',
        model: {
            defaults: {
                tagName: 'a',
                draggable: true,
                droppable: false,
                traits: [
                    { type: 'text', name: 'content', label: 'Text tlačítka', changeProp: true },
                    {
                        type: 'select', name: 'variant', label: 'Styl', changeProp: true,
                        options: [
                            { value: 'primary', name: 'Primary' },
                            { value: 'outline', name: 'Outline' },
                            { value: 'ghost',   name: 'Ghost' },
                            { value: 'white',   name: 'Bílé' },
                        ],
                    },
                    {
                        type: 'select', name: 'size', label: 'Velikost', changeProp: true,
                        options: [
                            { value: 'sm', name: 'Malé' },
                            { value: 'md', name: 'Střední' },
                            { value: 'lg', name: 'Velké' },
                            { value: 'xl', name: 'Extra velké' },
                        ],
                    },
                    { type: 'text', name: 'href',   label: 'URL odkazu' },
                    {
                        type: 'select', name: 'target', label: 'Otevřít',
                        options: [
                            { value: '_self',  name: 'Stejné okno' },
                            { value: '_blank', name: 'Nové okno' },
                        ],
                    },
                    { type: 'text', name: 'icon', label: 'FA ikona (fa-arrow-right)' },
                ],
            },
            init() {
                this.on('change:variant change:size change:content change:icon', this.updateStyles);
            },
            updateStyles() {
                const variant  = this.get('variant') || 'primary';
                const size     = this.get('size')    || 'md';
                const content  = this.get('content') || 'Tlačítko';
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
                    { type: 'text',   name: 'src', label: 'URL obrázku' },
                    { type: 'text',   name: 'alt', label: 'Alt text (SEO)' },
                    { type: 'text',   name: 'title', label: 'Title' },
                    {
                        type: 'select', name: 'object-fit', label: 'Object fit',
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
                        label: 'YouTube URL nebo ID videa',
                        placeholder: 'https://www.youtube.com/watch?v=...',
                        changeProp: true,
                    },
                    { type: 'checkbox', name: 'ytAutoplay',  label: 'Autoplay',                   changeProp: true },
                    { type: 'checkbox', name: 'ytControls',  label: 'Zobrazit ovládání',           changeProp: true },
                    { type: 'checkbox', name: 'ytRel',       label: 'Doporučená videa na konci',   changeProp: true },
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
                        label: 'Zdroj videa',
                        placeholder: 'https://example.com/video.mp4',
                        accept: 'video',
                        changeProp: true,
                    },
                    { type: 'checkbox', name: 'videoControls', label: 'Ovládání', changeProp: true },
                    { type: 'checkbox', name: 'videoAutoplay', label: 'Autoplay', changeProp: true },
                    { type: 'checkbox', name: 'videoMuted',    label: 'Ztlumit',  changeProp: true },
                    { type: 'checkbox', name: 'videoLoop',     label: 'Opakovat', changeProp: true },
                ],
            },
            init() {
                this.on('change:videoSrc change:videoControls change:videoAutoplay change:videoMuted change:videoLoop', this.syncAttrs);
            },
            syncAttrs() {
                const attrs = { 'data-gjs-type': 'mp4-video', preload: 'metadata' };
                const src = this.get('videoSrc') || '';
                if (src) attrs.src = src;
                if (this.get('videoControls')) { attrs.controls = 'controls'; } else { delete attrs.controls; }
                if (this.get('videoAutoplay')) attrs.autoplay = 'autoplay';
                if (this.get('videoMuted'))    attrs.muted    = 'muted';
                if (this.get('videoLoop'))     attrs.loop     = 'loop';
                this.addAttributes(attrs);
            },
        },
        view: {
            onRender() { this.el.setAttribute('data-gjs-type', 'mp4-video'); },
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
                    { type: 'textarea', name: 'embedCode', label: 'HTML kód', changeProp: true },
                ],
            },
            init() {
                this.on('change:embedCode', () => {
                    this.components(this.get('embedCode') || '<!-- Vložte HTML kód -->');
                });
            },
        },
        view: {
            onRender() { this.el.setAttribute('data-gjs-type', 'html-embed'); },
        },
    });
}