<?php

namespace App\Filament\Widgets;

use App\Models\Room;
use Filament\Widgets\ChartWidget;

class OccupancyChart extends ChartWidget
{
    protected ?string $heading = 'Status Ketersediaan Kamar';
    
    // Urutan tampilan (Nomor 3, di samping atau bawah Revenue Chart)
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // 1. Hitung Total Kamar
        $totalRooms = Room::count();
        
        // 2. Hitung Kamar Terisi
        // Logic: Kamar yang punya setidaknya satu tenancy aktif
        $occupied = Room::whereHas('tenancies', function($q){
            $q->where('is_active', true);
        })->count();

        // 3. Hitung Kamar Kosong
        $empty = $totalRooms - $occupied;

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Kamar',
                    'data' => [$occupied, $empty],
                    'backgroundColor' => [
                        '#ef4444', // Merah (Terisi)
                        '#22c55e', // Hijau (Kosong)
                    ],
                    'hoverOffset' => 4
                ],
            ],
            'labels' => ['Terisi', 'Kosong'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
