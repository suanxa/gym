<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use Illuminate\Support\Facades\Auth;
use App\Models\Member;

class DashboardController extends Controller
{
     public function index()
    {
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->first();

        // Ambil data kehadiran 7 hari terakhir
        $days = [];
        $counts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $days[] = $date->translatedFormat('D'); // Nama hari (Sen, Sel, dst)
            
            $count = Presence::where('user_id', $user->id)
                ->whereDate('check_in', $date->format('Y-m-d'))
                ->count();
            $counts[] = $count;
        }

        return view('member.dashboard', compact('member', 'days', 'counts'));
    }
}
