<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Approval;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        // Memuat relasi vehicle dan driver yang ada di Model
        $bookings = Booking::with(['vehicle', 'driver'])
            ->get()
            ->map(function ($booking) {
                // Membuat relasi 'approver1' dan 'approver2' secara dinamis tanpa mengubah file Model
                $booking->setRelation('approver1', User::find($booking->approver_1_id));
                $booking->setRelation('approver2', User::find($booking->approver_2_id));
                return $booking;
            });

        return view('admin.booking.table', compact('bookings'));
    }

    /**
     * Menampilkan daftar booking khusus APPROVER
     * URL: GET approver/bookings (Route Name: approver.bookings.index)
     */
    public function approverIndexList()
    {
        $user = Auth::user();

        $bookings = Booking::with(['vehicle', 'driver'])
            ->where('approver_1_id', $user->id)
            ->orWhere('approver_2_id', $user->id)
            ->get()
            ->map(function ($booking) {
                $booking->setRelation('approver1', User::find($booking->approver_1_id));
                $booking->setRelation('approver2', User::find($booking->approver_2_id));
                return $booking;
            });

        return view('approver.booking.table', compact('bookings'));
    }

    public function create()
    {
        $vehicles = Vehicle::all();
        $drivers = Driver::all();
        $approvers = User::where('role', 'approver')->get();
        return view('admin.booking.form', compact('vehicles', 'drivers', 'approvers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required',
            'driver_id' => 'required',
            'approver_1_id' => 'required',
            'approver_2_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        Booking::create([
            'vehicle_id' => $request->vehicle_id,
            'driver_id' => $request->driver_id,
            'admin_id' => Auth::id(),
            'approver_1_id' => $request->approver_1_id,
            'approver_2_id' => $request->approver_2_id,
            'status' => 'pending',
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        // PERBAIKAN: Diarahkan kembali ke index milik admin yang baru
        return redirect()->route('admin.booking.index')->with('success', 'Persetujuan berhasil diajukan.');
    }

    public function destroy($id)
    {
        // 1. Cari data booking berdasarkan ID, jika tidak ada akan memunculkan error 404
        $booking = Booking::findOrFail($id);

        // 2. Hapus data tersebut
        $booking->delete();

        // 3. Alihkan halaman kembali dengan pesan sukses
        return redirect()->route('admin.booking.index')
            ->with('success', 'Data persetujuan kendaraan berhasil dihapus.');
    }

    public function approve(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $user = Auth::user();
        $newStatus = $booking->status;
        $level = 0;

        if ($user->id == $booking->approver_1_id && $booking->status == 'pending') {
            $newStatus = 'approved_lvl_1';
            $level = 1;
        } elseif ($user->id == $booking->approver_2_id && $booking->status == 'approved_lvl_1') {
            $newStatus = 'approved_final';
            $level = 2;
        }

        if ($level > 0) {
            $booking->update(['status' => $newStatus]);

            Approval::create([
                'booking_id' => $booking->id,
                'approver_id' => $user->id,
                'level' => $level,
                'status' => 'approved',
            ]);
            return back()->with('success', 'Persetujuan Level ' . $level . ' berhasil.');
        }

        return back()->with('error', 'Anda tidak memiliki otoritas untuk menyetujui tahap ini.');
    }
}
