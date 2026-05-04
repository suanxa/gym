<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::latest()->get();
        return view('admin.expenses.index', compact('expenses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required',
            'amount' => 'required|numeric',
            'expense_date' => 'required|date',
            'category' => 'required'
        ]);

        Expense::create($validated);
        return redirect()->back()->with('success', 'Pengeluaran berhasil dicatat');
    }
}