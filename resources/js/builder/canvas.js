export function setupCanvas(editor, { contactUrl, csrfToken }) {
    editor.on('canvas:frame:load', () => {
        const win = editor.Canvas.getWindow();
        win?.AOS?.init?.({ once: true });

        const canvasDoc = editor.Canvas.getDocument();
        if (!canvasDoc) return;

        attachContactFormHandler(canvasDoc, { contactUrl, csrfToken });

        if (!canvasDoc.getElementById('builder-base')) {
            const style = canvasDoc.createElement('style');
            style.id = 'builder-base';
            style.textContent = `*, *::before, *::after { box-sizing: border-box; } img, video, iframe { max-width: 100%; }`;
            canvasDoc.head.appendChild(style);
        }
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
            status.textContent = 'Zpráva odeslána. Brzy se ozveme!';
        } catch {
            status.style.display = 'block';
            status.style.background = '#fef2f2';
            status.style.color = '#b91c1c';
            status.textContent = 'Nastala chyba. Zkuste to prosím znovu.';
        } finally {
            btn.disabled = false;
        }
    });
}
