<?php

namespace App\Filament\Resources\Groups\RelationManagers;

use App\Models\Bill;
use App\Models\Room;
use Filament\Actions\Action;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TenanciesRelationManager extends RelationManager
{
        protected static string $relationship = 'tenancies';
        protected static ?string $title = 'Daftar Penyewa (Tenancies)';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                    Section::make('Info Sewa')
                    ->schema([
                        // 1. PILIH KAMAR (Filter sesuai Grup ini saja)
                        Select::make('room_id')
                            ->label('Pilih Kamar')
                            ->options(function (RelationManager $livewire) {
                                // Ambil Group ID yang sedang dibuka
                                $groupId = $livewire->getOwnerRecord()->id;

                                // Cari kamar di grup ini yang Published
                                // Opsional: Tambah ->available() jika ingin sembunyikan yang penuh
                                return Room::where('group_id', $groupId)
                                    ->where('is_published', true)
                                    ->pluck('number', 'id');
                            })
                            ->searchable()
                            ->required(),

                        DatePicker::make('start_date')
                            ->label('Mulai Sewa')
                            ->default(now())
                            ->required(),

                        TextInput::make('price_per_month')
                            ->label('Harga/Bulan')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                            
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                    ])->columns(2),

                    Section::make('Data Penghuni')
                        ->schema([
                            Repeater::make('tenants')
                                ->relationship()
                                ->schema([
                                    TextInput::make('name')
                                        ->required()
                                        ->label('Nama Lengkap'),
                                    
                                    TextInput::make('email')
                                        ->label('Email (opsional)')
                                        ->email(),
                                    
                                    FileUpload::make('ktp_image')
                                        ->label('Foto KTP (opsional)')
                                        ->disk('public')
                                        ->directory('ktp-uploads')
                                        ->image(),
                                
                                    Toggle::make('is_main_tenant')
                                        ->label('Penanggung Jawab')
                                        ->helperText('Pilih jika orang ini adalah penanggung jawab.')
                                        ->default(false)
                                        ->live(),
                                    
                                    TextInput::make('phone_number')
                                        ->label('No. HP (WA)')
                                        ->tel()
                                        ->visible(fn (Get $get): bool => $get('is_main_tenant') === true)
                                        ->required(fn (Get $get): bool => $get('is_main_tenant') === true),
                                ])
                                ->columns(2)
                                ->minItems(1)
                                ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),
                        ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('room.number')
                    ->label('Kamar')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('mainTenant.name')
                    ->label('Penanggung Jawab')
                    ->searchable(),

                TextColumn::make('price_per_month')
                    ->money('IDR')
                    ->label('Harga'),

                TextColumn::make('start_date')
                    ->date('d M Y')
                    ->label('Mulai'),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // CUSTOM CREATE ACTION (PENTING!)
                // Karena ini HasManyThrough, Filament butuh bantuan untuk Create
                CreateAction::make()
                    ->label('Buat Sewa Baru')
                    ->using(function (array $data, string $model): Model {
                        // 1. Simpan Header Tenancy
                        // Kita tidak perlu set group_id karena tenancy nyambung ke room_id
                        $tenancy = $model::create($data);
                        
                        // 2. Handle Tenants (Repeater logic handled by relationship() in form, 
                        // tapi kita perlu set Main Tenant manual setelah save)
                        // Filament biasanya handle relasi HasMany otomatis, tapi untuk safety:
                        
                        return $tenancy;
                    })
                    ->after(function ($record) {
                        // A. Set Main Tenant (Orang Pertama)
                        $firstTenant = $record->tenants()->orderBy('id')->first();
                        if ($firstTenant) {
                            $firstTenant->update(['is_main_tenant' => true]);
                        }

                        // B. Generate Tagihan Awal (Lunas)
                        $initialBill = Bill::create([
                            'tenancy_id'     => $record->id,
                            'month_name'     => now()->format('F Y'),
                            'due_date'       => now(),
                            'amount'         => $record->price_per_month,
                            'order_sequence' => 1,
                        ]);

                        // B.2 Buat Transaksi Lunas Cash untuk Tagihan Awal
                        $transaction = \App\Models\Transaction::create([
                            'tenancy_id'   => $record->id,
                            'order_id'     => 'CASH-' . strtoupper(uniqid()), // Prefix khusus untuk manual cash
                            'gross_amount' => $record->price_per_month,
                            'status'       => 'settlement', // 'settlement' menandakan lunas di Midtrans/Sistem
                            'payment_type' => 'cash',
                        ]);

                        // Hubungkan transaksi dengan tagihan awal
                        // Gunakan attach() jika menggunakan relasi Many-to-Many (tabel pivot bill_transaction)
                        $transaction->bills()->attach($initialBill->id); 
                        
                        // CATATAN: Jika tabel 'bills' Anda menggunakan kolom 'transaction_id', 
                        // hapus baris attach() di atas dan gunakan baris di bawah ini:
                        // $initialBill->update(['transaction_id' => $transaction->id]);

                        // C. Generate Tagihan Bulan Depan (Unpaid)
                        Bill::create([
                            'tenancy_id'     => $record->id,
                            'month_name'     => now()->addMonth()->format('F Y'),
                            'due_date'       => now()->addMonth()->startOfMonth()->addDays(4),
                            'amount'         => $record->price_per_month,
                            'order_sequence' => 2,
                        ]);
                    })
            ])
            ->recordActions([
                EditAction::make(),
                // DissociateAction::make(),
                DeleteAction::make(),
                Action::make('print_qr')
                    ->label('Cetak QR')
                    ->icon('heroicon-o-qr-code')
                    ->color('info') // Warna Biru
                    ->url(fn ($record) => route('tenancy.print-qr', $record)) // Arahkan ke route cetak
                    ->openUrlInNewTab(), // Buka tab baru biar gak ganggu admin
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
