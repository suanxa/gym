<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index()
    {
        $prices = Price::all();
        return view('admin.payments.harga', compact('prices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'price' => 'required|numeric',
            'registration_fee' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        Price::create($request->all());
        return back()->with('success', 'Paket baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'price' => 'required|numeric',
            'registration_fee' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $price = Price::findOrFail($id);
        $price->update($request->all());

        return redirect()->back()->with('success', 'Harga kategori ' . $price->category . ' berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $price = Price::findOrFail($id);
        $price->delete();
        return back()->with('success', 'Data paket berhasil dihapus.');
    }
}