<?php

namespace App\Http\Livewire;

use App\Models\Data;
use Livewire\Component;

class Filters extends Component
{
    public function mount()
    {
        $user = auth()->user();
        if (! $user) {
            die;
        }

        $this->types = Data::whereUserId($user->id)->pluck('item_type')->unique()->toArray();
        $this->data = Data::whereUserId($user->id)
            ->where('item_type', $this->types[0])
            ->get();

        $this->current = $this->data->keys()->first();
    }

    public function loadItem($key)
    {
        $this->current = $key;
    }
}
