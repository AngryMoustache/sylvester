<?php

namespace App\Rambo;

use AngryMoustache\Rambo\Fields\IDField;
use AngryMoustache\Rambo\Fields\TextField;
use AngryMoustache\Rambo\Resource;

class Data extends Resource
{
    public function itemName()
    {
        return $this->item->item_type . ':' . $this->item->item_id;
    }

    public function fields()
    {
        return [
            IDField::make(),

            TextField::make('item_type'),

            TextField::make('item_id'),

            TextField::make('stringData', 'Data')
                ->hideFrom(['index'])
                ->searchable(),
        ];
    }

    public function canCreate()
    {
        return false;
    }

    public function canEdit()
    {
        return false;
    }
}
