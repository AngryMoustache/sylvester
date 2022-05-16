<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = [
        'data_id',
        'key',
        'type',
        'value',
    ];

    public $casts = [
        'value' => 'json',
    ];

    public function data()
    {
        return $this->belongsTo(Data::class);
    }
}
