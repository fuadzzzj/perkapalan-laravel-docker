<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'pendingCount' => User::query()->where('membership_status', 'pending')->count(),
            'memberCount' => User::query()->where('role', 'anggota')->where('membership_status', 'approved')->count(),
            'guruCount' => User::query()->where('role', 'guru')->count(),
        ]);
    }
}
