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
        $monthlyData = Booking::select(
            DB::raw("COUNT(*) as count"),
            DB::raw("MONTHNAME(created_at) as month")
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy(DB::raw("MIN(created_at)"), 'ASC')
            ->get();

        $labels = $monthlyData->pluck('month');
        $values = $monthlyData->pluck('count');

        // ==========================================
        // 2. GRAFIK PEMAKAIAN KENDARAAN
        // ==========================================
        $vehicleData = Booking::select('vehicle_id', DB::raw('COUNT(*) as total'))
            ->where('status', 'approved_final')
            ->groupBy('vehicle_id')
            ->with('vehicle')
            ->get();

        $vehicleLabels = [];
        $vehicleValues = [];

        foreach ($vehicleData as $data) {
            $vehicleLabels[] = ($data->vehicle->name ?? 'N/A') . ' [' . ($data->vehicle->plate_number ?? '-') . ']';
            $vehicleValues[] = $data->total;
        }

        // ==========================================
        // 3. LOG STATUS PERSETUJUAN (Grafik Baru Anda)
        // ==========================================
        // Ambil count dari 4 status spesifik yang ada pada alur birokrasi aplikasi
        $statusCounts = [
            'pending' => Booking::where('status', 'pending')->count(),
            'approved_lvl_1' => Booking::where('status', 'approved_lvl_1')->count(),
            'approved_final' => Booking::where('status', 'approved_final')->count(),
            'rejected' => Booking::where('status', 'rejected')->count(), // Menghitung status ditolak
        ];

        return view('admin.dashboard', [
            'labels' => $labels,
            'values' => $values,
            'vehicleLabels' => json_encode($vehicleLabels),
            'vehicleValues' => json_encode($vehicleValues),
            // Kirim variabel total individu untuk info box di template barunya
            'totalPending' => $statusCounts['pending'],
            'totalApproved' => $statusCounts['approved_final'],
            // Mengubah nilai asosiatif ke array urut [pending, lvl_1, final, rejected] untuk dibaca JavaScript
            'statusCounts' => json_encode(array_values($statusCounts))
        ]);
    }

    public function approverIndex()
    {
        $userId = auth()->id();

        // 1. QUERY UNTUK ANTREAN TABEL (Data Lama Anda)
        $pendingApprovals = Booking::where(function ($q) use ($userId) {
            $q->where('approver_1_id', $userId)->where('status', 'pending');
        })->orWhere(function ($q) use ($userId) {
            $q->where('approver_2_id', $userId)->where('status', 'approved_lvl_1');
        })->with(['vehicle', 'driver'])->get();


        // 2. QUERY GRAFIK LOG STATUS (Terfilter Khusus untuk Approver ini)
        // Menghitung status dari semua transaksi yang melibatkan user ini sebagai Approver 1 maupun Approver 2
        $statusCounts = [
            'pending' => Booking::where(function ($q) use ($userId) {
                $q->where('approver_1_id', $userId)->orWhere('approver_2_id', $userId);
            })->where('status', 'pending')->count(),

            'approved_lvl_1' => Booking::where(function ($q) use ($userId) {
                $q->where('approver_1_id', $userId)->orWhere('approver_2_id', $userId);
            })->where('status', 'approved_lvl_1')->count(),

            'approved_final' => Booking::where(function ($q) use ($userId) {
                $q->where('approver_1_id', $userId)->orWhere('approver_2_id', $userId);
            })->where('status', 'approved_final')->count(),

            'rejected' => Booking::where(function ($q) use ($userId) {
                $q->where('approver_1_id', $userId)->orWhere('approver_2_id', $userId);
            })->where('status', 'rejected')->count(),
        ];

        // Hitung ringkasan total khusus untuk teks info pembantu di atas grafik
        $totalHistory = array_sum($statusCounts);
        $myPending = Booking::where('approver_1_id', $userId)->where('status', 'pending')
            ->orWhere('approver_2_id', $userId)->where('status', 'approved_lvl_1')
            ->count();

        return view('approver.dashboard', [
            'pendingApprovals' => $pendingApprovals,
            'totalHistory' => $totalHistory,
            'myPending' => $myPending,
            'approverCounts' => json_encode(array_values($statusCounts)) // Array urut untuk JS
        ]);
    }
}