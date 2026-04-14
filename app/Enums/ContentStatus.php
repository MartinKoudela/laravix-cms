<?php

namespace App\Enums;

enum ContentStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case SCHEDULED = 'scheduled';
}
