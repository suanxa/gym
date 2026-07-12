<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
    'user_id', 'phone_number', 'address','date_of_birth', 'gender',
    'type', 'student_card', 'status', 'membership_expiry', 'profile_picture',
];

public function user() {
    return $this->belongsTo(User::class);
}

public function payments()
    {
        return $this->hasMany(Payment::class, 'user_id', 'user_id');
    }
}
