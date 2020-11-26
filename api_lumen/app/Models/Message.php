<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'message', 'user_id', 'receiver_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
