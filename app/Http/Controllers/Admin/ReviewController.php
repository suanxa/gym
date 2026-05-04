<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review; // Pastikan model Review sudah dibuat
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        // Ambil review terbaru, muat relasi user agar tahu siapa yang kirim
        $reviews = Review::with('user')->latest()->get();
        return view('admin.reviews.index', compact('reviews'));
    }

    public function togglePublish($id)
    {
        $review = Review::findOrFail($id);
        
        // Switch status: jika 1 jadi 0, jika 0 jadi 1
        $review->is_published = !$review->is_published;
        $review->save();

        $status = $review->is_published ? 'ditampilkan' : 'disembunyikan';
        return redirect()->back()->with('success', "Review berhasil $status di Landing Page!");
    }
}