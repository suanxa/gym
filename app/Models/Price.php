<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'category',
        'price',
        'registration_fee',
        'description',
    ];

    /**
     * Casting atribut ke tipe data tertentu.
     * Ini memastikan nominal uang selalu dibaca sebagai angka (integer/float).
     */
    protected $casts = [
        'price' => 'integer',
        'registration_fee' => 'integer',
    ];
}