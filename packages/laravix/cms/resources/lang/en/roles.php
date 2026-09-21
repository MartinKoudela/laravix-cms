<?php

return [
    'singular' => 'role',
    'plural' => 'roles',
    'full_access' => 'Full access',
    'types' => [
        'system' => 'System',
        'custom' => 'Custom',
    ],
    'fields' => [
        'permissions' => 'Permissions',
        'preset' => 'Start from',
    ],
    'sections' => [
        'permissions' => 'Permissions',
    ],
    'messages' => [
        'preset_hint' => 'Copy the permissions of a system role as a starting point.',
        'permissions_hint' => 'Tick what members of this role are allowed to do on this site. The admin role always has full access.',
        'in_use' => 'This role is still assigned to users or pending invitations and cannot be deleted.',
        'cannot_grant' => 'You cannot grant the :permission permission because you do not hold it yourself.',
        'cannot_assign' => 'You cannot assign a role with more permissions than you have.',
        'last_admin' => 'This user is the only admin of the site. Promote someone else first.',
    ],
];
