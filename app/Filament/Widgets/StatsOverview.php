<?php

namespace App\Filament\Widgets;

use App\Models\Bill;
use App\Models\Room;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    // Mengatur agar widget ini refresh otomatis tiap 15 detik (Realtime feel)
    protected ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        // 1. Hitung Pemasukan Bulan Ini (Hanya yang Settlement/Lunas)
        $incomeThisMonth = Transaction::where('status', 'settlement')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('gross_amount');

        // Hitung Pemasukan Bulan Lalu (Untuk perbandingan)
        $incomeLastMonth = Transaction::where('status', 'settlement')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->sum('gross_amount');
        
        // Logika Deskripsi (Naik/Turun)
        $incomeDesc = $incomeThisMonth > $incomeLastMonth ? 'Naik dari bulan lalu' : 'Turun dari bulan lalu';
        $incomeIcon = $incomeThisMonth > $incomeLastMonth ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $incomeColor = $incomeThisMonth > $incomeLastMonth ? 'success' : 'danger';

        // 2. Hitung Okupansi Kamar
        $totalRooms = Room::count();
        $occupiedRooms = Room::whereHas('tenancies', function($q){
            $q->where('is_active', true);
        })->count();
        
        // 3. Tagihan Belum Lunas (Unpaid Bills)
        $unpaidBills = Bill::unpaid()->count();

        return [
            Stat::make('Pemasukan Bulan Ini', 'Rp ' . number_format($incomeThisMonth, 0, ',', '.'))
                ->description($incomeDesc)
                ->descriptionIcon($incomeIcon)
                ->color($incomeColor)
                ->chart([7, 2, 10, 3, 15, 4, 17]), // Dummy chart kecil di background kartu

            Stat::make('Kamar Terisi', $occupiedRooms . ' / ' . $totalRooms)
                ->description('Total Kapasitas Kost')
                ->descriptionIcon('heroicon-m-home')
                ->color('primary'),

            Stat::make('Tagihan Belum Lunas', $unpaidBills)
                ->description('Perlu ditindaklanjuti')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('warning'),
        ];
    }
}
