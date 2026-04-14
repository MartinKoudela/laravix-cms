<?php

namespace App\Enums;

enum SiteRole: string
{
    case ADMIN = 'admin';
    case EDITOR = 'editor';
    case VIEWER = 'viewer';
}
