const SVG_DESKTOP = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>';
const SVG_TABLET  = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="2" width="16" height="20" rx="2"/><circle cx="12" cy="18" r="1" fill="currentColor"/></svg>';
const SVG_MOBILE  = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2"/><circle cx="12" cy="18" r="1" fill="currentColor"/></svg>';

const BUTTONS = [
    { id: 'btn-device-desktop', device: 'Desktop', label: SVG_DESKTOP, active: true  },
    { id: 'btn-device-tablet',  device: 'Tablet',  label: SVG_TABLET,  active: false },
    { id: 'btn-device-mobile',  device: 'Mobile',  label: SVG_MOBILE,  active: false },
];

export function setupDevicePanel(editor) {
    function switchDevice(name) {
        editor.setDevice(name);
        BUTTONS.forEach(({ id, device }) => {
            editor.Panels.getButton('topbar-devices', id)?.set('active', device === name);
        });
    }

    editor.Panels.addPanel({
        id: 'topbar-devices',
        el: '#topbar-devices',
        buttons: BUTTONS.map(({ id, device, label, active }) => ({
            id,
            active,
            togglable: false,
            label,
            command: { run: () => switchDevice(device), stop: () => {} },
            attributes: { title: device },
        })),
    });
}
