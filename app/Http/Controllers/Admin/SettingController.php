<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        // Ambil data setting pertama, jika tidak ada buat objek kosong
        $setting = Setting::first() ?? new Setting();
        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $setting = Setting::first() ?? new Setting();
        
        $data = $request->except(['logo', 'banner']);

        // Handle Upload Logo
        if ($request->hasFile('logo')) {
            if ($setting->logo) Storage::disk('public')->delete($setting->logo);
            $data['logo'] = $request->file('logo')->store('settings', 'public');
        }

        // Handle Upload Banner
        if ($request->hasFile('banner')) {
            if ($setting->banner) Storage::disk('public')->delete($setting->banner);
            $data['banner'] = $request->file('banner')->store('settings', 'public');
        }

        // Simpan data (update jika ada, create jika tidak ada)
        Setting::updateOrCreate(['id' => 1], $data);

        return redirect()->back()->with('success', 'Pengaturan website berhasil diperbarui!');
    }
}