export function openMediaPicker(mediaItems, accept, callback) {
    const filtered = accept === 'video'
        ? mediaItems.filter(m => m.type && m.type.startsWith('video/'))
        : accept === 'image'
        ? mediaItems.filter(m => !m.type || m.type.startsWith('image/'))
        : mediaItems;

    const overlay = document.createElement('div');
    overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:99999;display:flex;align-items:center;justify-content:center;padding:24px;';

    const modal = document.createElement('div');
    modal.style.cssText = 'background:#18181b;border:1px solid #27272a;border-radius:12px;width:800px;max-width:100%;max-height:80vh;display:flex;flex-direction:column;overflow:hidden;';

    const header = document.createElement('div');
    header.style.cssText = 'display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #27272a;flex-shrink:0;';
    header.innerHTML = `
        <span style="font-size:14px;font-weight:600;color:#e4e4e7;display:flex;align-items:center;gap:8px;">
            <i class="fa-solid fa-photo-film" style="color:#2563eb;"></i> Vybrat soubor
        </span>
        <button type="button" style="background:none;border:none;color:#71717a;cursor:pointer;padding:4px;line-height:1;display:flex;align-items:center;">
            <i class="fa-solid fa-xmark" style="font-size:16px;"></i>
        </button>`;
    header.querySelector('button').addEventListener('click', () => overlay.remove());

    const grid = document.createElement('div');
    grid.style.cssText = 'display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;padding:20px;overflow-y:auto;flex:1;';

    if (filtered.length === 0) {
        grid.innerHTML = `<div style="grid-column:1/-1;text-align:center;color:#52525b;padding:48px 0;font-size:13px;">
            <i class="fa-solid fa-photo-film" style="font-size:2rem;display:block;margin:0 auto 12px;color:#3f3f46;"></i>
            Žádná média tohoto typu. Nahrajte soubory v sekci Média.
        </div>`;
    } else {
        filtered.forEach(item => {
            const card = document.createElement('div');
            card.style.cssText = 'border:2px solid #3f3f46;border-radius:8px;overflow:hidden;cursor:pointer;transition:border-color .15s,transform .1s;';
            card.addEventListener('mouseenter', () => { card.style.borderColor = '#2563eb'; card.style.transform = 'scale(1.02)'; });
            card.addEventListener('mouseleave', () => { card.style.borderColor = '#3f3f46'; card.style.transform = ''; });

            const isVideo = item.type && item.type.startsWith('video/');
            card.innerHTML = isVideo
                ? `<div style="background:#27272a;height:90px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-film" style="font-size:2rem;color:#3f3f46;"></i></div>`
                : `<img src="${item.src}" alt="${item.name}" loading="lazy" style="width:100%;height:90px;object-fit:cover;display:block;">`;
            card.innerHTML += `<div style="padding:8px 10px;background:#1c1c1f;"><p style="font-size:11px;color:#a1a1aa;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${item.name}">${item.name}</p></div>`;

            card.addEventListener('click', () => { callback(item.src); overlay.remove(); });
            grid.appendChild(card);
        });
    }

    modal.append(header, grid);
    overlay.appendChild(modal);
    document.body.appendChild(overlay);
    overlay.addEventListener('click', e => { if (e.target === overlay) overlay.remove(); });
}

export function setupMediaTrait(editor, { mediaItems, csrfToken, uploadUrl }) {
    editor.TraitManager.addType('media-url', {
        createInput({ trait }) {
            const accept = trait.get('accept') || 'all';
            const wrap = document.createElement('div');
            wrap.style.cssText = 'display:flex;flex-direction:column;gap:6px;';

            const urlInput = document.createElement('input');
            urlInput.type = 'text';
            urlInput.placeholder = trait.get('placeholder') || 'https://...';
            urlInput.style.cssText = 'width:100%;padding:6px 10px;border:1px solid #3f3f46;border-radius:6px;background:#27272a;color:#e4e4e7;font-size:12px;box-sizing:border-box;outline:none;';
            urlInput.addEventListener('focus', () => { urlInput.style.borderColor = '#2563eb'; });
            urlInput.addEventListener('blur',  () => { urlInput.style.borderColor = '#3f3f46'; });
            urlInput.addEventListener('change', () => trait.setValue(urlInput.value));

            const btnRow = document.createElement('div');
            btnRow.style.cssText = 'display:flex;gap:6px;';

            const btnMedia = document.createElement('button');
            btnMedia.type = 'button';
            btnMedia.innerHTML = '<i class="fa-solid fa-photo-film"></i> Z médií';
            btnMedia.style.cssText = 'flex:1;padding:7px 8px;background:#27272a;color:#a1a1aa;border:1px solid #3f3f46;border-radius:6px;font-size:12px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:5px;transition:color .15s,border-color .15s;';
            btnMedia.addEventListener('mouseenter', () => { btnMedia.style.color = '#fff'; btnMedia.style.borderColor = '#71717a'; });
            btnMedia.addEventListener('mouseleave', () => { btnMedia.style.color = '#a1a1aa'; btnMedia.style.borderColor = '#3f3f46'; });
            btnMedia.addEventListener('click', () => openMediaPicker(mediaItems, accept, url => {
                urlInput.value = url;
                trait.setValue(url);
            }));

            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.style.cssText = 'display:none;';
            fileInput.accept = accept === 'video' ? 'video/*' : accept === 'image' ? 'image/*' : '*/*';

            const btnUpload = document.createElement('button');
            btnUpload.type = 'button';
            btnUpload.innerHTML = '<i class="fa-solid fa-upload"></i> Nahrát';
            btnUpload.style.cssText = 'flex:1;padding:7px 8px;background:#27272a;color:#a1a1aa;border:1px solid #3f3f46;border-radius:6px;font-size:12px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:5px;transition:color .15s,border-color .15s;';
            btnUpload.addEventListener('mouseenter', () => { btnUpload.style.color = '#fff'; btnUpload.style.borderColor = '#71717a'; });
            btnUpload.addEventListener('mouseleave', () => { btnUpload.style.color = '#a1a1aa'; btnUpload.style.borderColor = '#3f3f46'; });
            btnUpload.addEventListener('click', () => fileInput.click());

            fileInput.addEventListener('change', async () => {
                const file = fileInput.files[0];
                if (!file || !uploadUrl) return;

                btnUpload.disabled = true;
                btnUpload.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Nahrávám…';

                const formData = new FormData();
                formData.append('file', file);

                try {
                    const res = await fetch(uploadUrl, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: formData,
                    });
                    const text = await res.text();
                    if (!res.ok) {
                        let msg = `HTTP ${res.status}`;
                        try { const j = JSON.parse(text); msg = j?.message || j?.errors?.file?.[0] || msg; } catch {}
                        throw new Error(msg);
                    }
                    const data = JSON.parse(text);
                    urlInput.value = data.src;
                    trait.setValue(data.src);
                    mediaItems.push({ id: data.id, src: data.src, name: data.name, type: data.type });
                } catch (err) {
                    alert(`Nahrávání selhalo: ${err.message}`);
                } finally {
                    btnUpload.disabled = false;
                    btnUpload.innerHTML = '<i class="fa-solid fa-upload"></i> Nahrát';
                    fileInput.value = '';
                }
            });

            btnRow.append(btnMedia, btnUpload, fileInput);
            wrap.append(urlInput, btnRow);
            return wrap;
        },
        onUpdate({ elInput, component, trait }) {
            const input = elInput.querySelector('input[type="text"]');
            if (input) input.value = component.get(trait.get('name')) || '';
        },
    });
}