<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenancy;
use App\Models\Bill;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GenerateMonthlyBills extends Command
{
    protected $signature = 'bill:generate-daily-check';
    protected $description = 'Cek setiap hari: Siapa yang ulang tahun sewa hari ini?';

    public function handle()
    {
        $today = Carbon::now();
        $currentDay = $today->day;
        $currentMonthInfo = $today->format('F Y');

        $this->info("Memulai pengecekan harian untuk tanggal: {$currentDay}...");

        $activeTenancies = Tenancy::where('is_active', true)->get();

        $count = 0;

        foreach ($activeTenancies as $tenancy) {
            $startDate = Carbon::parse($tenancy->start_date);
            
            if ($startDate->day != $currentDay) {
                
                if ($currentDay == $today->daysInMonth && $startDate->day > $today->daysInMonth) {
                } else {
                    continue; 
                }
            }

            $alreadyBilled = Bill::where('tenancy_id', $tenancy->id)
                ->where('month_name', $currentMonthInfo)
                ->exists();

            if ($alreadyBilled) {
                continue;
            }

            $lastSequence = $tenancy->bills()->max('order_sequence') ?? 0;
            
            DB::transaction(function () use ($tenancy, $currentMonthInfo, $today, $lastSequence) {
                Bill::create([
                    'tenancy_id'     => $tenancy->id,
                    'month_name'     => $currentMonthInfo,
                    'due_date'       => $today->copy()->addDays(3),
                    'amount'         => $tenancy->price_per_month,
                    'order_sequence' => $lastSequence + 1,
                ]);
            });

            $this->info("Tagihan dibuat untuk ID: {$tenancy->id} (Tgl Sewa: {$startDate->day})");
            $count++;
        }

        $this->info("Selesai! {$count} tagihan baru dibuat hari ini.");
    }
}