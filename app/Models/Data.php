<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    protected $fillable = [
        'user_id',
        'item_type',
        'item_id',
        'data',
        'result',
    ];

    protected $casts = [
        'data' => 'json',
        'result' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStringDataAttribute()
    {
        return $this->attributes['data'];
    }

    public function getStringResultAttribute()
    {
        return $this->attributes['result'];
    }
}
