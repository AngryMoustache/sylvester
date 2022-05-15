<?php

namespace App\Rambo;

use AngryMoustache\Rambo\Fields\BooleanField;
use AngryMoustache\Rambo\Fields\IDField;
use AngryMoustache\Rambo\Fields\PasswordField;
use AngryMoustache\Rambo\Fields\TextField;
use AngryMoustache\Rambo\Resource;

class User extends Resource
{
    public $displayName = 'name';

    public function fields()
    {
        return [
            IDField::make(),

            TextField::make('name')
                ->rules('required'),

            TextField::make('email', 'E-mail')
                ->rules('required|email'),

            PasswordField::make('password'),

            BooleanField::make('has_token')
                ->hideFrom(['create', 'edit']),
        ];
    }
}
