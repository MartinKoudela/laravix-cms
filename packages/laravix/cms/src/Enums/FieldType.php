<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Enums;

enum FieldType: string
{
    case TEXT = 'text';
    case TEXTAREA = 'textarea';
    case RICH_TEXT = 'rich_text';
    case MARKDOWN = 'markdown';
    case IMAGE = 'image';
    case FILE = 'file';
    case BOOLEAN = 'boolean';
    case SELECT = 'select';
    case URL = 'url';
    case DATE = 'date';
    case DATETIME = 'datetime';
    case NUMBER = 'number';
    case COLOR = 'color';
}
