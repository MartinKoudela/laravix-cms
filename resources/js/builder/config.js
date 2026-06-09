import grapesjsPresetWebpage from 'grapesjs-preset-webpage';
import { FONT_AWESOME_CSS, GOOGLE_FONTS } from './constants';
import { t } from './trans';

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

export function buildConfig({ canvasCss, mediaItems }) {
    const FONT_OPTIONS = [
        { value: 'inherit',   name: t('val_font_inherit', '— Inherit —') },
        ...GOOGLE_FONTS.map(f => ({ value: `'${f}', sans-serif`, name: f })),
        { value: 'serif',     name: t('val_font_serif', 'Serif') },
        { value: 'monospace', name: t('val_font_monospace', 'Monospace') },
    ];

    return {
        container: '#gjs',
        height: '100%',
        width: 'auto',
        storageManager: false,
        undoManager: { trackChanges: true },
        canvas: {
            styles: [canvasCss, FONT_AWESOME_CSS, 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css'],
            scripts: [
                'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js',
                'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
            ],
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
            noAssets: t('no_assets', 'No media — upload files in the Media section.'),
        },
        plugins: [grapesjsPresetWebpage],
        pluginsOpts: {
            [grapesjsPresetWebpage]: {
                blocks: [],
                modalImportTitle: 'Import HTML',
                modalImportLabel: '',
                modalImportContent: '',
                filestackOpts: null,
            },
        },
        styleManager: {
            sectors: [
                {
                    name: t('sector_layout', 'Layout'),
                    open: true,
                    properties: [
                        {
                            label: t('prop_display', 'Display'),
                            property: 'display',
                            type: 'select',
                            options: [
                                { value: 'block',        name: t('val_block', 'Block') },
                                { value: 'flex',         name: t('val_flex', 'Flex') },
                                { value: 'grid',         name: t('val_grid', 'Grid') },
                                { value: 'inline-block', name: t('val_inline_block', 'Inline block') },
                                { value: 'inline',       name: t('val_inline', 'Inline') },
                                { value: 'none',         name: t('val_none', 'Hidden') },
                            ],
                        },
                        {
                            label: t('prop_direction', 'Direction'),
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
                            label: t('prop_align_x', 'Align X'),
                            property: 'justify-content',
                            type: 'radio',
                            requires: { display: ['flex'] },
                            options: [
                                { value: 'flex-start',    name: JC.start,   title: 'Start' },
                                { value: 'center',        name: JC.center,  title: 'Center' },
                                { value: 'flex-end',      name: JC.end,     title: 'End' },
                                { value: 'space-between', name: JC.between, title: 'Space between' },
                                { value: 'space-around',  name: JC.around,  title: 'Space around' },
                            ],
                        },
                        {
                            label: t('prop_align_y', 'Align Y'),
                            property: 'align-items',
                            type: 'radio',
                            requires: { display: ['flex'] },
                            options: [
                                { value: 'flex-start', name: AI.start,   title: 'Start' },
                                { value: 'center',     name: AI.center,  title: 'Center' },
                                { value: 'flex-end',   name: AI.end,     title: 'End' },
                                { value: 'stretch',    name: AI.stretch, title: 'Stretch' },
                            ],
                        },
                        {
                            label: t('prop_wrap', 'Wrap'),
                            property: 'flex-wrap',
                            type: 'radio',
                            requires: { display: ['flex'] },
                            options: [
                                { value: 'nowrap', name: t('val_no_wrap', 'No') },
                                { value: 'wrap',   name: t('val_wrap', 'Yes') },
                            ],
                        },
                        { property: 'gap', label: t('prop_gap', 'Gap'), type: 'integer', units: ['px', 'rem', '%', 'em'] },
                        {
                            property: 'width',
                            label: t('prop_width', 'Width'),
                            type: 'integer',
                            units: ['px', '%', 'rem', 'vw', 'em'],
                            min: 0,
                        },
                        {
                            property: 'max-width',
                            label: t('prop_max_width', 'Max width'),
                            type: 'integer',
                            units: ['px', '%', 'rem', 'vw', 'em'],
                            min: 0,
                        },
                        {
                            property: 'height',
                            label: t('prop_height', 'Height'),
                            type: 'integer',
                            units: ['px', '%', 'rem', 'vh', 'em'],
                            min: 0,
                        },
                        {
                            property: 'min-height',
                            label: t('prop_min_height', 'Min height'),
                            type: 'integer',
                            units: ['px', '%', 'rem', 'vh', 'em'],
                            min: 0,
                        },
                        {
                            property: 'overflow',
                            label: t('prop_overflow', 'Overflow'),
                            type: 'select',
                            options: [
                                { value: 'visible', name: t('val_visible', 'Visible') },
                                { value: 'hidden',  name: t('val_hidden', 'Hidden') },
                                { value: 'auto',    name: t('val_auto', 'Auto') },
                                { value: 'scroll',  name: t('val_scroll', 'Scroll') },
                            ],
                        },
                    ],
                },
                {
                    name: t('sector_spacing', 'Spacing'),
                    open: false,
                    properties: [
                        {
                            property: 'padding',
                            label: t('prop_padding', 'Padding'),
                            type: 'composite',
                            properties: [
                                { property: 'padding-top',    label: t('prop_top', 'Top'),    type: 'integer', units: ['px', 'rem', '%', 'em'] },
                                { property: 'padding-right',  label: t('prop_right', 'Right'), type: 'integer', units: ['px', 'rem', '%', 'em'] },
                                { property: 'padding-bottom', label: t('prop_bottom', 'Bottom'), type: 'integer', units: ['px', 'rem', '%', 'em'] },
                                { property: 'padding-left',   label: t('prop_left', 'Left'),  type: 'integer', units: ['px', 'rem', '%', 'em'] },
                            ],
                        },
                        {
                            property: 'margin',
                            label: t('prop_margin', 'Margin'),
                            type: 'composite',
                            properties: [
                                { property: 'margin-top',    label: t('prop_top', 'Top'),    type: 'integer', units: ['px', 'rem', '%', 'em', 'auto'] },
                                { property: 'margin-right',  label: t('prop_right', 'Right'), type: 'integer', units: ['px', 'rem', '%', 'em', 'auto'] },
                                { property: 'margin-bottom', label: t('prop_bottom', 'Bottom'), type: 'integer', units: ['px', 'rem', '%', 'em', 'auto'] },
                                { property: 'margin-left',   label: t('prop_left', 'Left'),  type: 'integer', units: ['px', 'rem', '%', 'em', 'auto'] },
                            ],
                        },
                    ],
                },
                {
                    name: t('sector_typography', 'Typography'),
                    open: false,
                    properties: [
                        { label: t('prop_font', 'Font'), property: 'font-family', type: 'select', options: FONT_OPTIONS },
                        { property: 'font-size',      label: t('prop_font_size', 'Size'),           type: 'integer', units: ['px', 'rem', 'em', '%', 'vw'] },
                        {
                            label: t('prop_font_weight', 'Weight'),
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
                        { property: 'line-height',    label: t('prop_line_height', 'Line height'),    type: 'integer', units: ['', 'px', 'em'] },
                        { property: 'letter-spacing', label: t('prop_letter_spacing', 'Letter spacing'), type: 'integer', units: ['px', 'em'] },
                        { property: 'color',          label: t('prop_color', 'Text color'),           type: 'color' },
                        {
                            label: t('prop_text_align', 'Alignment'),
                            property: 'text-align',
                            type: 'radio',
                            options: [
                                { value: 'left',    name: t('val_left', 'Left') },
                                { value: 'center',  name: t('val_center', 'Center') },
                                { value: 'right',   name: t('val_right', 'Right') },
                                { value: 'justify', name: t('val_justify', 'Justify') },
                            ],
                        },
                        {
                            label: t('prop_text_transform', 'Transform'),
                            property: 'text-transform',
                            type: 'select',
                            options: [
                                { value: 'none',       name: t('val_none_transform', 'None') },
                                { value: 'uppercase',  name: 'UPPERCASE' },
                                { value: 'lowercase',  name: 'lowercase' },
                                { value: 'capitalize', name: 'Capitalize' },
                            ],
                        },
                        {
                            label: t('prop_text_decoration', 'Decoration'),
                            property: 'text-decoration',
                            type: 'select',
                            options: [
                                { value: 'none',         name: t('val_no_decoration', 'None') },
                                { value: 'underline',    name: t('val_underline', 'Underline') },
                                { value: 'line-through', name: t('val_line_through', 'Strikethrough') },
                            ],
                        },
                        { property: 'text-shadow', label: t('prop_text_shadow', 'Text shadow') },
                    ],
                },
                {
                    name: t('sector_background', 'Background'),
                    open: false,
                    properties: [
                        { property: 'background-color',    label: t('prop_bg_color', 'Background color'), type: 'color' },
                        { property: 'background-image',    label: t('prop_bg_image', 'Image / Gradient') },
                        {
                            label: t('prop_bg_size', 'Background size'),
                            property: 'background-size',
                            type: 'select',
                            options: [
                                { value: 'auto',    name: t('val_bg_auto', 'Auto') },
                                { value: 'cover',   name: t('val_bg_cover', 'Cover') },
                                { value: 'contain', name: t('val_bg_contain', 'Contain') },
                            ],
                        },
                        { property: 'background-position', label: t('prop_bg_position', 'Background position') },
                        {
                            label: t('prop_bg_repeat', 'Repeat'),
                            property: 'background-repeat',
                            type: 'select',
                            options: [
                                { value: 'no-repeat', name: t('val_no_repeat', 'No') },
                                { value: 'repeat',    name: t('val_repeat', 'Repeat') },
                                { value: 'repeat-x',  name: t('val_repeat_x', 'Repeat X') },
                                { value: 'repeat-y',  name: t('val_repeat_y', 'Repeat Y') },
                            ],
                        },
                    ],
                },
                {
                    name: t('sector_borders', 'Borders & Shadow'),
                    open: false,
                    properties: [
                        { property: 'border-radius', label: t('prop_border_radius', 'Rounding'),    type: 'integer', units: ['px', 'rem', '%'] },
                        { property: 'border',        label: t('prop_border', 'Border') },
                        { property: 'border-color',  label: t('prop_border_color', 'Border color'), type: 'color' },
                        { property: 'box-shadow',    label: t('prop_box_shadow', 'Box shadow') },
                        { property: 'opacity',       label: t('prop_opacity', 'Opacity'),           type: 'slider', min: 0, max: 1, step: 0.01 },
                    ],
                },
                {
                    name: t('sector_position', 'Position'),
                    open: false,
                    properties: [
                        {
                            label: t('prop_position', 'Position type'),
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
                        { property: 'top',     label: t('prop_top', 'Top'),    type: 'integer', units: ['px', '%', 'rem'] },
                        { property: 'right',   label: t('prop_right', 'Right'), type: 'integer', units: ['px', '%', 'rem'] },
                        { property: 'bottom',  label: t('prop_bottom', 'Bottom'), type: 'integer', units: ['px', '%', 'rem'] },
                        { property: 'left',    label: t('prop_left', 'Left'),  type: 'integer', units: ['px', '%', 'rem'] },
                        { property: 'z-index', label: t('prop_z_index', 'Z-index'), type: 'integer' },
                    ],
                },
                {
                    name: t('sector_transform', 'Transform & Animations'),
                    open: false,
                    properties: [
                        { property: 'transform',  label: t('prop_transform', 'Transform') },
                        { property: 'transition', label: t('prop_transition', 'Transition') },
                        {
                            property: 'cursor',
                            label: t('prop_cursor', 'Cursor'),
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