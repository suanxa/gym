<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
    'user_id', 'member_id', 'external_customer_name', 'amount', 'duration', 
    'payment_date', 'proof_of_payment', 'description', 'status'
];

public function user() {
    return $this->belongsTo(User::class);
}

public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
