<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Approval;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\PeriodicBookingExport;
use Maatwebsite\Excel\Facades\Excel;

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
        // 1. Cari data booking berdasarkan ID
        $booking = Booking::findOrFail($id);

        // 2. PROTEKSI: Cek apakah status sudah berubah dari 'pending'
        // Jika statusnya 'approved_lvl_1', 'approved_final', atau bahkan jika sudah di-reject,
        // Admin tidak boleh menghapusnya secara sepihak.
        if ($booking->status !== 'pending') {
            return redirect()->route('admin.booking.index')
                ->with('error', 'Data tidak dapat dihapus karena sudah diproses atau disetujui oleh salah satu approver!');
        }

        // 3. Hapus data log approval yang terkait (jika ada)
        Approval::where('booking_id', $booking->id)->delete();

        // 4. Hapus data booking
        $booking->delete();

        // 5. Alihkan halaman kembali dengan pesan sukses
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

    public function reject(Request $request, $id)
    {
        // 1. Cari data booking berdasarkan ID
        $booking = Booking::findOrFail($id);
        $user = auth()->user();
        $level = 0;

        // 2. Tentukan level approver mana yang melakukan penolakan
        if ($user->id == $booking->approver_1_id && $booking->status == 'pending') {
            $level = 1;
        } elseif ($user->id == $booking->approver_2_id && $booking->status == 'approved_lvl_1') {
            $level = 2;
        }

        // 3. Jika user yang login adalah approver yang sah dan posisi statusnya sesuai
        if ($level > 0) {
            // Ubah status booking menjadi 'rejected'
            $booking->update(['status' => 'rejected']);

            // Catat aksi penolakan ke dalam tabel log approvals
            \App\Models\Approval::create([
                'booking_id' => $booking->id,
                'approver_id' => $user->id,
                'level' => $level,
                'status' => 'rejected', // Simpan status rejected di log
            ]);

            return back()->with('success', 'Pengajuan pemesanan kendaraan berhasil ditolak.');
        }

        return back()->with('error', 'Anda tidak memiliki otoritas untuk menolak pengajuan pada tahap ini.');
    }

    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);
        $user = Auth::user();

        // LOGIKA 1: Pembatalan oleh Approver 1
        if ($user->id == $booking->approver_1_id) {
            // Approver 1 hanya bisa membatalkan jika status saat ini 'approved_lvl_1' 
            // (Artinya Approver 2 BELUM melakukan tindakan approve)
            if ($booking->status == 'approved_lvl_1') {
                $booking->update(['status' => 'pending']);

                // Hapus data log approval milik approver 1 untuk booking ini
                Approval::where('booking_id', $booking->id)
                    ->where('approver_id', $user->id)
                    ->where('level', 1)
                    ->delete();

                return back()->with('success', 'Persetujuan Anda berhasil dibatalkan. Status kembali ke Pending.');
            }

            return back()->with('error', 'Tidak bisa membatalkan, Approver 2 sudah menyetujui pesanan ini.');
        }

        // LOGIKA 2: Pembatalan oleh Approver 2
        if ($user->id == $booking->approver_2_id) {
            // Approver 2 bisa membatalkan jika status sudah 'approved_final'
            if ($booking->status == 'approved_final') {
                $booking->update(['status' => 'approved_lvl_1']);

                // Hapus data log approval milik approver 2 untuk booking ini
                Approval::where('booking_id', $booking->id)
                    ->where('approver_id', $user->id)
                    ->where('level', 2)
                    ->delete();

                return back()->with('success', 'Persetujuan Final berhasil dibatalkan. Status kembali ke Level 1.');
            }

            return back()->with('error', 'Anda belum melakukan approval pada data ini.');
        }

        return back()->with('error', 'Anda tidak memiliki hak akses pembatalan.');
    }

    public function exportPeriodic()
    {
        $tahunSekarang = date('Y');

        return Excel::download(
            new PeriodicBookingExport, // 2. UBAH DI SINI: Hilangkan huruf 's' agar sesuai dengan pola PSR
            'laporan-periodik-kendaraan-' . $tahunSekarang . '.xlsx'
        );
    }
}
