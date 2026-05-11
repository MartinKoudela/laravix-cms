import grapesjs from 'grapesjs';
import 'grapesjs/dist/css/grapes.min.css';

import { buildConfig }       from './builder/config';
import { setupDevicePanel }  from './builder/devices';
import { preloadFonts, setupStyleManager } from './builder/styles';
import { setupMediaTrait }   from './builder/media';
import { registerComponents } from './builder/components';
import { registerBlocks }    from './builder/blocks';
import { setupCanvas }       from './builder/canvas';
import { setupSave }         from './builder/save';

const el = document.getElementById('gjs');
if (!el) throw new Error('GrapeJS container not found');

const savedData   = el.dataset.projectData ? JSON.parse(el.dataset.projectData) : null;
const saveUrl     = el.dataset.saveUrl;
const csrfToken   = el.dataset.csrf;
const canvasCss   = el.dataset.canvasCss;
const mediaItems  = JSON.parse(el.dataset.mediaItems  || '[]');
const contactUrl  = el.dataset.contactUrl;
const uploadUrl   = el.dataset.uploadUrl;

preloadFonts();

const editor = grapesjs.init(buildConfig({ canvasCss, mediaItems }));

setupDevicePanel(editor);
setupStyleManager(editor);
setupMediaTrait(editor, { mediaItems, csrfToken, uploadUrl });
registerComponents(editor);
registerBlocks(editor);
setupCanvas(editor, { contactUrl, csrfToken });
setupSave(editor, { saveUrl, csrfToken });

if (savedData) {
    editor.loadProjectData(savedData);
}