import grapesjsPresetWebpage from 'grapesjs-preset-webpage';
import { FONT_AWESOME_CSS, GOOGLE_FONTS } from './constants';

const DIR = {
    row:           `<i class="fa-solid fa-arrow-right"></i>`,
    column:        `<i class="fa-solid fa-arrow-down"></i>`,
    rowReverse:    `<i class="fa-solid fa-arrow-left"></i>`,
    columnReverse: `<i class="fa-solid fa-arrow-up"></i>`,
};
const JC = {
    start:   `<i class="fa-solid fa-align-left"></i>`,
    center:  `<i class="fa-solid fa-align-center"></i>`,
    end:     `<i class="fa-solid fa-align-right"></i>`,
    between: `<i class="fa-solid fa-align-justify"></i>`,
    around:  `<i class="fa-solid fa-grip-lines-vertical"></i>`,
};
const R90 = 'transform:rotate(-90deg);display:inline-block';
const AI = {
    start:   `<i class="fa-solid fa-align-left" style="${R90}"></i>`,
    center:  `<i class="fa-solid fa-align-center" style="${R90}"></i>`,
    end:     `<i class="fa-solid fa-align-right" style="${R90}"></i>`,
    stretch: `<i class="fa-solid fa-align-justify" style="${R90}"></i>`,
};

const FONT_OPTIONS = [
    { value: 'inherit',   name: '— Zdědit —' },
    ...GOOGLE_FONTS.map(f => ({ value: `'${f}', sans-serif`, name: f })),
    { value: 'serif',     name: 'Serif' },
    { value: 'monospace', name: 'Monospace' },
];

export function buildConfig({ canvasCss, mediaItems }) {
    return {
        container: '#gjs',
        height: '100%',
        width: 'auto',
        storageManager: false,
        undoManager: { trackChanges: true },
        canvas: {
            styles: [canvasCss, FONT_AWESOME_CSS],
            scripts: ['https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js'],
            customBadgeLabel: (component) => component.getName(),
        },
        deviceManager: {
            devices: [
                { name: 'Desktop', width: '' },
                { name: 'Tablet',  width: '768px', widthMedia: '1024px' },
                { name: 'Mobile portrait', width: '390px', widthMedia: '768px' },
            ],
        },
        assetManager: {
            assets: mediaItems,
            upload: false,
            noAssets: 'Žádná média — nahryjte soubory v sekci Média.',
        },
        plugins: [grapesjsPresetWebpage],
        pluginsOpts: {
            [grapesjsPresetWebpage]: {
                blocks: [],
                modalImportTitle: 'Importovat HTML',
                modalImportLabel: '',
                modalImportContent: '',
                filestackOpts: null,
            },
        },
        styleManager: {
            sectors: [
                {
                    name: 'Rozložení',
                    open: true,
                    properties: [
                        {
                            label: 'Zobrazení',
                            property: 'display',
                            type: 'select',
                            options: [
                                { value: 'block',        name: 'Block' },
                                { value: 'flex',         name: 'Flex' },
                                { value: 'grid',         name: 'Grid' },
                                { value: 'inline-block', name: 'Inline block' },
                                { value: 'inline',       name: 'Inline' },
                                { value: 'none',         name: 'Skryto' },
                            ],
                        },
                        {
                            label: 'Směr',
                            property: 'flex-direction',
                            type: 'radio',
                            requires: { display: ['flex'] },
                            options: [
                                { value: 'row',            name: DIR.row,           title: 'Row →' },
                                { value: 'column',         name: DIR.column,        title: 'Column ↓' },
                                { value: 'row-reverse',    name: DIR.rowReverse,    title: 'Row reverse ←' },
                                { value: 'column-reverse', name: DIR.columnReverse, title: 'Column reverse ↑' },
                            ],
                        },
                        {
                            label: 'Zarovnání X',
                            property: 'justify-content',
                            type: 'radio',
                            requires: { display: ['flex'] },
                            options: [
                                { value: 'flex-start',    name: JC.start,   title: 'Start' },
                                { value: 'center',        name: JC.center,  title: 'Střed' },
                                { value: 'flex-end',      name: JC.end,     title: 'End' },
                                { value: 'space-between', name: JC.between, title: 'Space between' },
                                { value: 'space-around',  name: JC.around,  title: 'Space around' },
                            ],
                        },
                        {
                            label: 'Zarovnání Y',
                            property: 'align-items',
                            type: 'radio',
                            requires: { display: ['flex'] },
                            options: [
                                { value: 'flex-start', name: AI.start,   title: 'Start' },
                                { value: 'center',     name: AI.center,  title: 'Střed' },
                                { value: 'flex-end',   name: AI.end,     title: 'End' },
                                { value: 'stretch',    name: AI.stretch, title: 'Stretch' },
                            ],
                        },
                        {
                            label: 'Zalamování',
                            property: 'flex-wrap',
                            type: 'radio',
                            requires: { display: ['flex'] },
                            options: [
                                { value: 'nowrap', name: 'Ne' },
                                { value: 'wrap',   name: 'Ano' },
                            ],
                        },
                        { property: 'gap',        label: 'Gap' },
                        { property: 'width',      label: 'Šířka' },
                        { property: 'max-width',  label: 'Max. šířka' },
                        { property: 'min-height', label: 'Min. výška' },
                        {
                            property: 'overflow',
                            label: 'Overflow',
                            type: 'select',
                            options: [
                                { value: 'visible', name: 'Visible' },
                                { value: 'hidden',  name: 'Hidden' },
                                { value: 'auto',    name: 'Auto' },
                                { value: 'scroll',  name: 'Scroll' },
                            ],
                        },
                    ],
                },
                {
                    name: 'Odsazení',
                    open: false,
                    properties: [
                        {
                            property: 'padding',
                            label: 'Padding',
                            type: 'composite',
                            properties: [
                                { property: 'padding-top',    label: 'Nahoře', type: 'integer', units: ['px', 'rem', '%', 'em'] },
                                { property: 'padding-right',  label: 'Vpravo', type: 'integer', units: ['px', 'rem', '%', 'em'] },
                                { property: 'padding-bottom', label: 'Dole',   type: 'integer', units: ['px', 'rem', '%', 'em'] },
                                { property: 'padding-left',   label: 'Vlevo',  type: 'integer', units: ['px', 'rem', '%', 'em'] },
                            ],
                        },
                        {
                            property: 'margin',
                            label: 'Margin',
                            type: 'composite',
                            properties: [
                                { property: 'margin-top',    label: 'Nahoře', type: 'integer', units: ['px', 'rem', '%', 'em', 'auto'] },
                                { property: 'margin-right',  label: 'Vpravo', type: 'integer', units: ['px', 'rem', '%', 'em', 'auto'] },
                                { property: 'margin-bottom', label: 'Dole',   type: 'integer', units: ['px', 'rem', '%', 'em', 'auto'] },
                                { property: 'margin-left',   label: 'Vlevo',  type: 'integer', units: ['px', 'rem', '%', 'em', 'auto'] },
                            ],
                        },
                    ],
                },
                {
                    name: 'Typografie',
                    open: false,
                    properties: [
                        { label: 'Font', property: 'font-family', type: 'select', options: FONT_OPTIONS },
                        { property: 'font-size',      label: 'Velikost',      type: 'integer', units: ['px', 'rem', 'em', '%', 'vw'] },
                        {
                            label: 'Tučnost',
                            property: 'font-weight',
                            type: 'select',
                            options: [
                                { value: '300', name: 'Light 300' },
                                { value: '400', name: 'Regular 400' },
                                { value: '500', name: 'Medium 500' },
                                { value: '600', name: 'SemiBold 600' },
                                { value: '700', name: 'Bold 700' },
                                { value: '800', name: 'ExtraBold 800' },
                                { value: '900', name: 'Black 900' },
                            ],
                        },
                        { property: 'line-height',    label: 'Výška řádku',   type: 'integer', units: ['', 'px', 'em'] },
                        { property: 'letter-spacing', label: 'Mezery písmen', type: 'integer', units: ['px', 'em'] },
                        { property: 'color',          label: 'Barva textu',   type: 'color' },
                        {
                            label: 'Zarovnání',
                            property: 'text-align',
                            type: 'radio',
                            options: [
                                { value: 'left',    name: 'Vlevo' },
                                { value: 'center',  name: 'Střed' },
                                { value: 'right',   name: 'Vpravo' },
                                { value: 'justify', name: 'Do bloku' },
                            ],
                        },
                        {
                            label: 'Transformace',
                            property: 'text-transform',
                            type: 'select',
                            options: [
                                { value: 'none',       name: 'Žádná' },
                                { value: 'uppercase',  name: 'UPPERCASE' },
                                { value: 'lowercase',  name: 'lowercase' },
                                { value: 'capitalize', name: 'Capitalize' },
                            ],
                        },
                        {
                            label: 'Dekorace',
                            property: 'text-decoration',
                            type: 'select',
                            options: [
                                { value: 'none',         name: 'Žádná' },
                                { value: 'underline',    name: 'Podtržení' },
                                { value: 'line-through', name: 'Přeškrtnutí' },
                            ],
                        },
                        { property: 'text-shadow', label: 'Stín textu' },
                    ],
                },
                {
                    name: 'Pozadí',
                    open: false,
                    properties: [
                        { property: 'background-color',    label: 'Barva pozadí',      type: 'color' },
                        { property: 'background-image',    label: 'Obrázek / Gradient' },
                        {
                            label: 'Velikost pozadí',
                            property: 'background-size',
                            type: 'select',
                            options: [
                                { value: 'auto',    name: 'Auto' },
                                { value: 'cover',   name: 'Cover' },
                                { value: 'contain', name: 'Contain' },
                            ],
                        },
                        { property: 'background-position', label: 'Pozice pozadí' },
                        {
                            label: 'Opakování',
                            property: 'background-repeat',
                            type: 'select',
                            options: [
                                { value: 'no-repeat', name: 'Ne' },
                                { value: 'repeat',    name: 'Opakovat' },
                                { value: 'repeat-x',  name: 'Opakovat X' },
                                { value: 'repeat-y',  name: 'Opakovat Y' },
                            ],
                        },
                    ],
                },
                {
                    name: 'Okraje & Stín',
                    open: false,
                    properties: [
                        { property: 'border-radius', label: 'Zaoblení',    type: 'integer', units: ['px', 'rem', '%'] },
                        { property: 'border',        label: 'Okraj' },
                        { property: 'border-color',  label: 'Barva okraje', type: 'color' },
                        { property: 'box-shadow',    label: 'Stín boxu' },
                        { property: 'opacity',       label: 'Průhlednost', type: 'slider', min: 0, max: 1, step: 0.01 },
                    ],
                },
                {
                    name: 'Pozice',
                    open: false,
                    properties: [
                        {
                            label: 'Typ pozice',
                            property: 'position',
                            type: 'select',
                            options: [
                                { value: 'static',   name: 'Static' },
                                { value: 'relative', name: 'Relative' },
                                { value: 'absolute', name: 'Absolute' },
                                { value: 'fixed',    name: 'Fixed' },
                                { value: 'sticky',   name: 'Sticky' },
                            ],
                        },
                        { property: 'top',     label: 'Nahoře', type: 'integer', units: ['px', '%', 'rem'] },
                        { property: 'right',   label: 'Vpravo', type: 'integer', units: ['px', '%', 'rem'] },
                        { property: 'bottom',  label: 'Dole',   type: 'integer', units: ['px', '%', 'rem'] },
                        { property: 'left',    label: 'Vlevo',  type: 'integer', units: ['px', '%', 'rem'] },
                        { property: 'z-index', label: 'Z-index', type: 'integer' },
                    ],
                },
                {
                    name: 'Transformace & Animace',
                    open: false,
                    properties: [
                        { property: 'transform',  label: 'Transform' },
                        { property: 'transition', label: 'Transition' },
                        {
                            property: 'cursor',
                            label: 'Kurzor',
                            type: 'select',
                            options: [
                                { value: 'default',     name: 'Default' },
                                { value: 'pointer',     name: 'Pointer' },
                                { value: 'text',        name: 'Text' },
                                { value: 'move',        name: 'Move' },
                                { value: 'not-allowed', name: 'Not allowed' },
                            ],
                        },
                    ],
                },
            ],
        },
    };
}
