<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class RevenueChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Pemasukan (6 Bulan Terakhir)';
    
    // Urutan tampilan di dashboard (Nomor 2, di bawah StatsOverview)
    protected static ?int $sort = 2;
    
    // Agar grafik memenuhi lebar (Full Width)
    // protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Menggunakan package Laravel Trend untuk grouping per bulan
        $data = Trend::query(
                // Filter: Hanya hitung transaksi yang SUKSES/LUNAS
                Transaction::whereIn('status', ['settlement', 'capture'])
            )
            ->between(
                start: now()->subMonths(6),
                end: now(),
            )
            ->perMonth()
            ->sum('gross_amount');

        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan (Rupiah)',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#3b82f6', // Warna Biru Brand (Tailwind Blue-500)
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)', // Biru transparan
                    'fill' => true,
                    'tension' => 0.4, // Membuat garis agak melengkung (smooth)
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
