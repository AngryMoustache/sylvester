<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'history';

    public $fillable = [
        'user_id',
        'form_params',
        'result_count',
    ];

    public $cast = [
        'form_params' => 'json',
        'result_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
