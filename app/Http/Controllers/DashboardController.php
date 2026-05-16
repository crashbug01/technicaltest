<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('approver.dashboard');
    }

    public function adminIndex()
    {
        // Gunakan strftime('%m') untuk mengambil angka bulan di SQLite
        $data = Booking::select(
            DB::raw("COUNT(*) as count"),
            DB::raw("MONTHNAME(created_at) as month") // Menggunakan MONTHNAME bawaan MySQL
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get();

        $labels = $data->pluck('month');
        $values = $data->pluck('count');

        return view('admin.dashboard', compact('labels', 'values'));
    }

    public function approverIndex()
    {
        $userId = auth()->id();

        // Ambil booking yang butuh persetujuan user ini
        $pendingApprovals = Booking::where(function ($q) use ($userId) {
            $q->where('approver_1_id', $userId)->where('status', 'pending');
        })->orWhere(function ($q) use ($userId) {
            $q->where('approver_2_id', $userId)->where('status', 'approved_lvl_1');
        })->get();

        return view('approver.dashboard', compact('pendingApprovals'));
    }
}