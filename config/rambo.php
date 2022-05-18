<?php

use AngryMoustache\Rambo\Resources\Administrator;
use AngryMoustache\Rambo\Resources\Attachment;
use App\Rambo\Data;
use App\Rambo\User;

return [
    'admin-route' => 'admin',
    'admin-guard' => 'rambo',
    'resources' => [
        Attachment::class,
        Administrator::class,
        User::class,
        Data::class,
    ],
    'navigation' => [
        'General' => [
            Administrator::class,
            Attachment::class,
        ],
        User::class,
        Data::class,
    ],
    'cropper' => [
        'formats' => [
            \AngryMoustache\Media\Formats\Thumb::class => 'Thumb',
        ],
    ],
];
