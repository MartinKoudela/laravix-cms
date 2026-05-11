export function setupSave(editor, { saveUrl, csrfToken }) {
    let saving = false;
    const saveBtn    = document.getElementById('btn-save');
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
            saveStatus.textContent = 'Uloženo';
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
}