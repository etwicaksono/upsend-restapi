<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegisterEvent extends Model
{
    protected $fillable = [
        'user_id', 'event_id',
    ];
}
