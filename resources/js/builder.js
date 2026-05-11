import grapesjs from 'grapesjs';
import 'grapesjs/dist/css/grapes.min.css';

const el = document.getElementById('gjs');
if (!el) throw new Error('GrapeJS container not found');

const savedData = el.dataset.projectData ? JSON.parse(el.dataset.projectData) : null;
const saveUrl = el.dataset.saveUrl;
const csrfToken = el.dataset.csrf;
const canvasCss = el.dataset.canvasCss;

const editor = grapesjs.init({
    container: '#gjs',
    height: '100%',
    width: 'auto',
    storageManager: false,
    canvas: {
        styles: [canvasCss],
    },
    assetManager: {
        assets: JSON.parse(el.dataset.mediaItems || '[]'),
        upload: false,
    },
});


const bm = editor.BlockManager;

bm.add('hero', {
    label: 'Hero',
    category: 'Sekce',
    content: `
        <section style="padding:80px 24px;text-align:center;background:#f9fafb;">
            <div style="max-width:720px;margin:0 auto;">
                <h1 style="font-size:3rem;font-weight:800;color:#111827;margin:0 0 16px;">Hlavní nadpis</h1>
                <p style="font-size:1.25rem;color:#6b7280;margin:0 0 32px;line-height:1.7;">Krátký popis nebo výzva k akci.</p>
                <a href="#" style="display:inline-block;padding:14px 32px;background:#111827;color:#fff;font-weight:600;border-radius:8px;text-decoration:none;">Začít</a>
            </div>
        </section>`,
});

bm.add('text', {
    label: 'Text',
    category: 'Sekce',
    content: `
        <section style="padding:64px 24px;">
            <div style="max-width:720px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;margin:0 0 16px;">Nadpis sekce</h2>
                <p style="font-size:1rem;color:#374151;line-height:1.8;margin:0;">Zde je text sekce. Klikni pro editaci.</p>
            </div>
        </section>`,
});

bm.add('cards', {
    label: 'Cards',
    category: 'Sekce',
    content: `
        <section style="padding:64px 24px;background:#f9fafb;">
            <div style="max-width:1100px;margin:0 auto;">
                <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 40px;">Sekce karet</h2>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;">
                    <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                        <h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0 0 8px;">Karta 1</h3>
                        <p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">Popis karty.</p>
                    </div>
                    <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                        <h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0 0 8px;">Karta 2</h3>
                        <p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">Popis karty.</p>
                    </div>
                    <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                        <h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0 0 8px;">Karta 3</h3>
                        <p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">Popis karty.</p>
                    </div>
                </div>
            </div>
        </section>`,
});

bm.add('columns', {
    label: 'Columns',
    category: 'Sekce',
    content: `
        <section style="padding:64px 24px;">
            <div style="max-width:1100px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;">
                <div>
                    <h2 style="font-size:2rem;font-weight:700;color:#111827;margin:0 0 16px;">Levý sloupec</h2>
                    <p style="font-size:1rem;color:#374151;line-height:1.8;margin:0;">Text levého sloupce.</p>
                </div>
                <div>
                    <h2 style="font-size:2rem;font-weight:700;color:#111827;margin:0 0 16px;">Pravý sloupec</h2>
                    <p style="font-size:1rem;color:#374151;line-height:1.8;margin:0;">Text pravého sloupce.</p>
                </div>
            </div>
        </section>`,
});

bm.add('cta', {
    label: 'CTA',
    category: 'Sekce',
    content: `
        <section style="padding:80px 24px;background:#111827;text-align:center;">
            <div style="max-width:600px;margin:0 auto;">
                <h2 style="font-size:2.25rem;font-weight:800;color:#fff;margin:0 0 16px;">Výzva k akci</h2>
                <p style="font-size:1.125rem;color:#9ca3af;margin:0 0 32px;">Krátký přesvědčivý text.</p>
                <a href="#" style="display:inline-block;padding:14px 32px;background:#fff;color:#111827;font-weight:700;border-radius:8px;text-decoration:none;">Kontaktovat nás</a>
            </div>
        </section>`,
});

bm.add('divider', {
    label: 'Divider',
    category: 'Sekce',
    content: '<div style="padding:32px 24px;"><hr style="border:none;border-top:1px solid #e5e7eb;"></div>',
});

bm.add('button', {
    label: 'Button',
    category: 'Elementy',
    content: '<a href="#" style="display:inline-block;padding:12px 28px;background:#111827;color:#fff;font-weight:600;border-radius:8px;text-decoration:none;font-size:.9375rem;">Tlačítko</a>',
});

bm.add('image', {
    label: 'Image',
    category: 'Elementy',
    content: '<img src="https://placehold.co/800x400?text=Image" style="width:100%;height:auto;display:block;border-radius:8px;" alt="">',
});


if (savedData) {
    editor.loadProjectData(savedData);
}


let saving = false;
const saveBtn = document.getElementById('btn-save');
const saveStatus = document.getElementById('save-status');

async function saveProject() {
    if (saving) return;
    saving = true;
    saveBtn.disabled = true;
    saveStatus.textContent = 'Ukládání…';

    try {
        const res = await fetch(saveUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                grapesjs_data: JSON.stringify(editor.getProjectData()),
                grapesjs_html: editor.getHtml() + '<style>' + editor.getCss() + '</style>',
            }),
        });

        if (!res.ok) throw new Error();
        saveStatus.textContent = 'Uloženo ✓';
        setTimeout(() => { saveStatus.textContent = ''; }, 2500);
    } catch {
        saveStatus.textContent = 'Chyba při ukládání';
    } finally {
        saving = false;
        saveBtn.disabled = false;
    }
}

saveBtn.addEventListener('click', saveProject);

document.addEventListener('keydown', (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        saveProject();
    }
});
