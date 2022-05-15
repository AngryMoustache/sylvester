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
}
