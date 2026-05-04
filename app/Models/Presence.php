<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $fillable = ['user_id', 'check_in'];

public function user() {
    return $this->belongsTo(User::class);
}
}
