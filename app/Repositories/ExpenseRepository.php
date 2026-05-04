<?php

namespace App\Repositories;
use App\Models\Expense;

class ExpenseRepository {
    public function getAll() { return Expense::latest()->get(); }
    public function create(array $data) { return Expense::create($data); }
}