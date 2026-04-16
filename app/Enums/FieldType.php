<?php

namespace App\Enums;

enum FieldType: string
{
    case TEXT = 'text';
    case TEXTAREA = 'textarea';
    case RICH_TEXT = 'rich_text';
    case IMAGE = 'image';
    case FILE = 'file';
    case BOOLEAN = 'boolean';
    case SELECT = 'select';
    case URL = 'url';
    case DATE = 'date';

}
