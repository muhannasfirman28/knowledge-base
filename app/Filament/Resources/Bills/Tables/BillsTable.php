<?php

namespace App\Filament\Resources\Bills\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Barryvdh\DomPDF\Facade\Pdf;

class BillsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tenancy.room.number')
                    ->label('Kamar')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('tenancy.mainTenant.name')
                    ->label('Penyewa')
                    ->searchable(),

                TextColumn::make('month_name')
                    ->label('Periode')
                    ->searchable(),

                TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('status_bayar')
                    ->label('Status Bayar')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        return $record->is_paid ? 'Lunas' : 'Belum Lunas';
                    })
                    ->color(fn ($state) => match ($state) {
                        'Lunas' => 'success',
                        'Belum Lunas' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn ($state) => match ($state) {
                        'Lunas' => 'heroicon-o-check-circle',
                        'Belum Lunas' => 'heroicon-o-x-circle',
                        default => null,
                    }),

                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('tanggal_tagihan')
                    ->form([
                        DatePicker::make('dari_tanggal'),
                        DatePicker::make('sampai_tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn ($query, $date) => $query->whereDate('due_date', '>=', $date)
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn ($query, $date) => $query->whereDate('due_date', '<=', $date)
                            );
                    }),

                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        '1' => 'Lunas',
                        '0' => 'Belum Bayar',
                    ])
                    ->attribute('is_paid'),
            ])
            ->headerActions([
                Action::make('cetak_laporan')
                    ->label('Cetak Laporan')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->action(function ($livewire) {
                        $query = $livewire->getFilteredTableQuery();
                        
                        // Menarik data beserta relasinya
                        $dataTagihan = $query->with(['tenancy.mainTenant', 'tenancy.room'])->get(); 

                        // Kalkulasi total pendapatan (berdasarkan is_paid == 1)
                        $totalPendapatan = $dataTagihan->where('is_paid', 1)->sum('amount');

                        // Generate PDF
                        $pdf = Pdf::loadView('reports.keuangan', [
                            'tagihan' => $dataTagihan,
                            'total' => $totalPendapatan,
                            'tanggal_cetak' => now()->format('d M Y H:i')
                        ]);

                        return response()->streamDownload(
                            fn () => print($pdf->output()), 
                            'Laporan_Keuangan_Kost_'.now()->format('Ymd_His').'.pdf'
                        );
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}