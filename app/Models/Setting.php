<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
    'site_name', 'description', 'phone', 'email', 'address', 'logo', 'banner', 'qris_image', 'bank_name', 'bank_account'
];
}
