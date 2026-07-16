<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Setting; // Pastikan import Setting
use App\Models\Price;
use App\Models\Review;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        // Ambil data setting
        $siteSetting = Setting::first();
        
        // Ambil data member
        $members = Member::all();
        $totalMembers = $members->count();
        $activeMembers = $members->where('status', 'active')->count();
        $packages = Price::all();
        $allPrices = Price::all();

        $reviews = Review::with(['user.member'])->where('is_published', 1)->get();
        $membershipPackages = $allPrices->filter(function($price) {
        return !in_array(strtolower($price->category), ['harian', 'guest', 'daily']);
        });

        // Mengambil yang Harian untuk ditampilkan di bagian lain (jika perlu)
        $dailyPrice = $allPrices->first(function($price) {
            return in_array(strtolower($price->category), ['harian', 'guest', 'daily']);
        });

        // Kirim semua variabel ke view
        return view('welcome', compact('totalMembers', 'activeMembers', 'siteSetting','packages', 'membershipPackages', 'dailyPrice', 'reviews'));
    }
}