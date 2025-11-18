<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Models\Bill;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Str;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Input Pembayaran')
                    ->schema([
                        Select::make('tenancy_id')
                            ->label('Penyewa')
                            ->options(fn() => \App\Models\Tenancy::with(['room', 'mainTenant'])
                                ->where('is_active', true)
                                ->get()
                                ->mapWithKeys(fn($t) => [$t->id => "{$t->room->number} - " . ($t->mainTenant->name ?? 'Guest')])
                            )
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn(Set $set) => $set('bill_ids', [])),

                        Select::make('bill_ids')
                            ->label('Tagihan yang Dibayar')
                            ->multiple()
                            ->options(function (Get $get) {
                                $tenancyId = $get('tenancy_id');
                                if (!$tenancyId) return [];
                                
                                return Bill::where('tenancy_id', $tenancyId)
                                    ->orderBy('order_sequence')
                                    ->get()
                                    ->mapWithKeys(fn($b) => [$b->id => "{$b->month_name} (Rp " . number_format($b->amount) . ")"]);
                            })
                            ->live()
                            ->required()
                            ->dehydrated(false) 
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $ids = $get('bill_ids') ?? [];
                                $total = Bill::whereIn('id', $ids)->sum('amount');
                                $set('gross_amount', $total);
                            }),

                        TextInput::make('gross_amount')
                            ->label('Total Bayar')
                            ->prefix('Rp')
                            ->numeric()
                            ->readOnly()
                            ->required(),

                        Select::make('payment_type')
                            ->options(['cash' => 'Tunai', 'manual_transfer' => 'Transfer Manual'])
                            ->default('cash')
                            ->required(),

                        Select::make('status')
                            ->options([
                                'settlement' => 'LUNAS',
                                'pending' => 'PENDING',
                                'cancel' => 'BATAL'
                            ])
                            ->default('settlement')
                            ->required(),
                            
                        TextInput::make('order_id')
                            ->default(fn() => 'TRX-' . strtoupper(Str::random(6)))
                            ->readOnly(),
                    ])->columns(2)
            ]);
    }
}
