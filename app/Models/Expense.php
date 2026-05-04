<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
    'item_name', 'amount', 'expense_date', 'category', 'note'
];
}
