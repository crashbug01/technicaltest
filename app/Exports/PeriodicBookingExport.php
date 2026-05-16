<?php

namespace App\Exports;

use App\Models\Booking;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PeriodicBookingExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * Ambil seluruh data booking tahun ini, diurutkan berdasarkan tanggal terkecil (Bulan Januari - Desember)
     */
    public function collection()
    {
        return Booking::with(['vehicle', 'driver'])
            ->whereYear('created_at', date('Y'))
            ->orderBy('created_at', 'ASC')
            ->get();
    }

    /**
     * Header Atas Tabel Excel
     */
    public function headings(): array
    {
        return [
            'ID Booking',
            'Bulan Pemesanan',
            'Nama Kendaraan',
            'Nomor Plat',
            'Nama Driver',
            'Tanggal Operasional Mulai',
            'Tanggal Operasional Selesai',
            'Status Persetujuan Akhir',
        ];
    }

    /**
     * Pemetaan Kolom Data
     */
    public function map($booking): array
    {
        // Terjemahkan nama status database agar rapi di laporan Excel
        $statusText = match ($booking->status) {
            'pending' => 'Menunggu Approver 1',
            'approved_lvl_1' => 'Disetujui Approver 1 (Pending Lvl 2)',
            'approved_final' => 'Disetujui Final (Selesai)',
            'rejected' => 'Ditolak / Dibatalkan',
            default => $booking->status,
        };

        return [
            $booking->id,
            // Mengambil Nama Bulan berbasis Lokalisasi Waktu (Contoh: January, February)
            $booking->created_at->format('F'),
            $booking->vehicle->name ?? 'N/A',
            $booking->vehicle->plate_number ?? 'N/A',
            $booking->driver->name ?? 'N/A',
            \Carbon\Carbon::parse($booking->start_date)->format('d-m-Y'),
            \Carbon\Carbon::parse($booking->end_date)->format('d-m-Y'),
            $statusText,
        ];
    }

    /**
     * Styling opsional agar tampilan baris judul Excel menjadi tebal (Bold)
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}