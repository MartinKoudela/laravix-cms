<?php

return [
    'singular' => 'invitation',
    'plural' => 'invitations',
    'fields' => [
        'invited_by' => 'Invited by',
        'expires' => 'Expires',
    ],
    'accept' => [
        'title' => 'You have been invited',
        'intro' => 'You are joining :site as :role.',
        'name' => 'Full name',
        'password' => 'Password',
        'password_confirmation' => 'Confirm password',
        'submit' => 'Accept invitation',
        'expires' => 'This invitation expires :time.',
    ],
    'statuses' => [
        'accepted' => 'Accepted',
        'pending' => 'Pending',
    ],
];
