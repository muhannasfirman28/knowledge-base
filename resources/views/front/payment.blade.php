@extends('layouts.app')

@section('title', 'Pembayaran & Riwayat - KostPedia')

@push('scripts')
    @php
        $snapUrl = config('services.midtrans.is_production') 
            ? 'https://app.midtrans.com/snap/snap.js' 
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    @endphp
    <script src="{{ $snapUrl }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
@endpush

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4 sm:px-6 lg:px-8 font-sans">
    
    <div class="max-w-4xl mx-auto mb-6">
        <a href="{{ route('home') }}" class="inline-flex items-center text-slate-500 hover:text-emerald-600 transition font-medium">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Beranda
        </a>
    </div>

    @if(session('success'))
    <div class="max-w-4xl mx-auto mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-2 shadow-sm animate-pulse">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-4xl mx-auto mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl flex items-center gap-2 shadow-sm">
        <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
    </div>
    @endif

    <div class="max-w-4xl mx-auto bg-white rounded-[2rem] shadow-sm border border-slate-200/60 overflow-hidden" 
         x-data="{ currentTab: 'tagihan' }"> 

        <div class="bg-slate-900 p-8 md:p-10 text-white relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-teal-500/20 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <div class="flex items-center gap-4 mb-3">
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-teal-600 rounded-2xl flex items-center justify-center text-2xl font-bold text-white shadow-lg shadow-emerald-500/30 uppercase border border-white/10">
                            {{ substr($tenancy->mainTenant->name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold capitalize tracking-tight">{{ $tenancy->mainTenant->name ?? 'Penghuni' }}</h2>
                            <p class="text-emerald-400 text-sm font-medium">Penyewa Utama</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-slate-300 mt-4 md:mt-2">
                        <span class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-lg border border-white/10 backdrop-blur-sm">
                            <i class="fa-solid fa-door-open text-emerald-400"></i> Unit {{ $tenancy->room->number }}
                        </span>
                        <span class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-lg border border-white/10 backdrop-blur-sm">
                            <i class="fa-solid fa-building text-emerald-400"></i> {{ $tenancy->room->group->name }}
                        </span>
                    </div>
                </div>
                
                <div class="text-left md:text-right w-full md:w-auto mt-4 md:mt-0">
                    <p class="text-xs text-slate-400 uppercase tracking-widest mb-1.5 font-semibold">ID Akses / Token</p>
                    <div class="inline-flex items-center gap-3 bg-slate-800/80 border border-slate-700 text-emerald-400 font-mono font-bold text-lg px-5 py-2.5 rounded-xl shadow-inner select-all cursor-pointer hover:bg-slate-800 transition" onclick="alert('Token ID tersalin!'); document.execCommand('copy');">
                        {{ $tenancy->id }}
                        <i class="fa-regular fa-copy text-slate-500 text-sm"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex border-b border-slate-100 bg-slate-50/50 px-6 pt-6 gap-2 overflow-x-auto hide-scroll">
            
            <button @click="currentTab = 'tagihan'"
                :class="currentTab === 'tagihan' ? 'bg-white text-emerald-600 border-t-2 border-emerald-500 shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-white/50'"
                class="px-5 py-3.5 rounded-t-xl font-bold text-sm transition-all duration-200 flex items-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-file-invoice-dollar"></i> Tagihan Baru
                @if($unpaidBills->count() > 0)
                    <span class="bg-rose-500 text-white text-[10px] px-2 py-0.5 rounded-full shadow-sm">{{ $unpaidBills->count() }}</span>
                @endif
            </button>

            <button @click="currentTab = 'pending'"
                :class="currentTab === 'pending' ? 'bg-white text-emerald-600 border-t-2 border-emerald-500 shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-white/50'"
                class="px-5 py-3.5 rounded-t-xl font-bold text-sm transition-all duration-200 flex items-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-spinner"></i> Sedang Berjalan
                @if($pendingTransactions->count() > 0)
                    <span class="bg-amber-500 text-white text-[10px] px-2 py-0.5 rounded-full shadow-sm animate-pulse">{{ $pendingTransactions->count() }}</span>
                @endif
            </button>

            <button @click="currentTab = 'riwayat'"
                :class="currentTab === 'riwayat' ? 'bg-white text-emerald-600 border-t-2 border-emerald-500 shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-white/50'"
                class="px-5 py-3.5 rounded-t-xl font-bold text-sm transition-all duration-200 flex items-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Lunas
            </button>
        </div>

        <div class="p-6 md:p-8 min-h-[400px]">
            
            <!-- BAGIAN TAB TAGIHAN -->
            <div x-show="currentTab === 'tagihan'" x-transition.opacity>
                
                <form id="payment-form">
                    <input type="hidden" name="tenancy_id" value="{{ $tenancy->id }}">
                    <input type="hidden" name="advance_months" id="advance_months" value="0">
                    <input type="hidden" id="price_per_month" value="{{ $tenancy->price_per_month ?? 0 }}">
                    
                    <!-- Fitur Pay-Advance (Pilih Cepat) Selalu Muncul -->
                    <div class="mb-6 bg-emerald-50/50 p-5 rounded-2xl border border-emerald-100 shadow-sm">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fa-solid fa-bolt text-amber-400"></i>
                            <h4 class="text-sm font-bold text-slate-700">Bayar Lebih Awal (Pay Advance)</h4>
                        </div>
                        <p class="text-xs text-slate-500 mb-4">Pilih tagihan beberapa bulan ke depan sekaligus (Pra-bayar) untuk menghindari telat bayar.</p>
                        
                        <div class="flex flex-wrap gap-3">
                            <button type="button" onclick="selectAdvance(3)" class="px-5 py-2 bg-white text-emerald-600 rounded-xl border border-emerald-200 hover:bg-emerald-50 hover:border-emerald-300 font-bold text-sm transition-all shadow-sm">
                                3 Bulan
                            </button>
                            <button type="button" onclick="selectAdvance(6)" class="px-5 py-2 bg-white text-emerald-600 rounded-xl border border-emerald-200 hover:bg-emerald-50 hover:border-emerald-300 font-bold text-sm transition-all shadow-sm">
                                6 Bulan
                            </button>
                            <button type="button" onclick="selectAdvance(12)" class="px-5 py-2 bg-white text-emerald-600 rounded-xl border border-emerald-200 hover:bg-emerald-50 hover:border-emerald-300 font-bold text-sm transition-all shadow-sm">
                                1 Tahun
                            </button>
                            <button type="button" onclick="selectAdvance('all')" class="px-5 py-2 bg-slate-800 text-white rounded-xl border border-slate-700 hover:bg-slate-700 font-bold text-sm transition-all shadow-sm">
                                Pilih Semua Tagihan
                            </button>
                        </div>
                    </div>

                    @if($unpaidBills->isEmpty())
                        <div class="flex flex-col items-center justify-center py-16 text-center">
                            <div class="w-24 h-24 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center text-4xl mb-6 shadow-sm border border-emerald-100">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800">Tidak Ada Tagihan Baru</h3>
                            <p class="text-slate-500 mt-2 max-w-sm">
                                Luar biasa! Anda tidak memiliki tunggakan pembayaran saat ini. Anda dapat menggunakan opsi pra-bayar di atas.
                            </p>
                        </div>
                    @else
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 mb-8 flex items-start gap-4 text-slate-600 shadow-sm">
                            <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center text-emerald-500 flex-shrink-0">
                                <i class="fa-solid fa-lightbulb"></i>
                            </div>
                            <div class="text-sm pt-1.5 leading-relaxed">
                                <strong class="text-slate-800">Sistem Berurutan:</strong> Silakan pilih tagihan. Sistem akan otomatis menceklis bulan sebelumnya untuk memastikan pembayaran berjalan urut.
                            </div>
                        </div>

                        <div class="space-y-4 mb-8">
                            @foreach($unpaidBills as $bill)
                            <label class="group relative flex items-center justify-between p-5 border border-slate-200 rounded-2xl cursor-pointer hover:border-emerald-400 hover:shadow-md transition-all duration-300 select-none bg-white" id="row-{{ $bill->id }}">
                                <div class="flex items-center gap-5">
                                    <div class="relative flex items-center justify-center h-6 w-6 flex-shrink-0">
                                        <input type="checkbox" name="bill_ids[]" value="{{ $bill->id }}" 
                                            data-amount="{{ $bill->amount }}"
                                            class="bill-checkbox peer appearance-none h-6 w-6 border-2 border-slate-300 rounded-md checked:bg-emerald-500 checked:border-emerald-500 transition-colors cursor-pointer">
                                        <i class="fa-solid fa-check text-white text-xs absolute opacity-0 peer-checked:opacity-100 pointer-events-none"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 text-lg group-hover:text-emerald-600 transition">{{ $bill->month_name }}</div>
                                        <div class="text-sm text-rose-500 font-medium mt-1 flex items-center gap-1.5">
                                            <i class="fa-regular fa-calendar-xmark"></i>
                                            Jatuh Tempo: {{ \Carbon\Carbon::parse($bill->due_date)->format('d M Y') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="block text-xl font-bold text-slate-800 font-mono tracking-tight">
                                        Rp {{ number_format($bill->amount, 0, ',', '.') }}
                                    </span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    @endif

                    <div class="flex flex-col md:flex-row justify-between items-center gap-6 pt-8 border-t border-slate-100 sticky bottom-0 bg-white z-10 pb-4 md:pb-0">
                        <div class="text-center md:text-left w-full md:w-auto">
                            <span class="text-slate-400 text-xs font-bold uppercase tracking-widest">Total Terpilih</span>
                            <div class="text-4xl font-black text-slate-900 mt-1" id="total-display">Rp 0</div>
                        </div>
                        
                        <button type="button" id="pay-button" disabled 
                            class="w-full md:w-auto bg-slate-200 text-slate-400 px-10 py-4 rounded-xl font-bold text-lg shadow-none flex items-center justify-center gap-3 transition-all duration-300 cursor-not-allowed">
                            <span>Proses Pembayaran</span>
                            <i class="fa-solid fa-arrow-right text-sm"></i>
                            <div id="loading-spinner" class="hidden w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                        </button>
                    </div>
                </form>
            </div>

            <div x-show="currentTab === 'pending'" x-transition.opacity style="display: none;">
                @if($pendingTransactions->isEmpty())
                    <div class="text-center py-16">
                        <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center text-3xl mx-auto mb-4 border border-slate-100">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <h3 class="text-slate-800 font-bold text-lg">Bersih!</h3>
                        <p class="text-slate-500 text-sm mt-1">Tidak ada transaksi yang sedang menunggu pembayaran.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($pendingTransactions as $trx)
                            @php
                                $expireDate = $trx->created_at->copy();
                                if(isset($expiryConfig)) {
                                    if($expiryConfig['unit'] === 'day') { $expireDate->addDays($expiryConfig['duration']); } 
                                    elseif($expiryConfig['unit'] === 'hour') { $expireDate->addHours($expiryConfig['duration']); } 
                                    elseif($expiryConfig['unit'] === 'minute') { $expireDate->addMinutes($expiryConfig['duration']); }
                                } else {
                                    $expireDate->addDay(); 
                                }
                                $isExpired = $expireDate->isPast();
                            @endphp

                        <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-md transition duration-300 {{ $isExpired ? 'opacity-70 bg-slate-50' : '' }}">
                            <div class="flex flex-col md:flex-row justify-between md:items-center gap-6">
                                <div class="flex-grow">
                                    <div class="flex items-center gap-3 mb-3">
                                        @if($isExpired)
                                            <span class="bg-rose-50 text-rose-600 text-[10px] font-bold px-2.5 py-1 rounded-md border border-rose-100 uppercase tracking-wide">
                                                Kadaluarsa
                                            </span>
                                        @else
                                            <span class="bg-amber-50 text-amber-600 text-[10px] font-bold px-2.5 py-1 rounded-md border border-amber-100 uppercase tracking-wide">
                                                Menunggu Bayar
                                            </span>
                                        @endif
                                        <span class="text-xs text-slate-400 font-mono border-l border-slate-200 pl-3">{{ $trx->order_id }}</span>
                                    </div>
                                    <div class="text-2xl font-bold text-slate-900 mb-1 tracking-tight">
                                        Rp {{ number_format($trx->gross_amount, 0, ',', '.') }}
                                    </div>
                                    <div class="text-sm text-slate-500 mb-3">
                                        Membayar: 
                                        @foreach($trx->bills as $bill)
                                            <span class="font-semibold text-slate-700">{{ $bill->month_name }}</span>{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </div>
                                    <div class="text-xs text-slate-400 flex items-center gap-2">
                                        <i class="fa-regular fa-clock"></i>
                                        Dibuat: {{ $trx->created_at->setTimezone('Asia/Jakarta')->format('d M H:i') }}
                                        @if(!$isExpired)
                                            <span class="text-rose-500 font-medium ml-2">• Expire: {{ $expireDate->setTimezone('Asia/Jakarta')->format('d M H:i') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-3 flex-shrink-0">
                                    <form action="{{ route('payment.cancel', $trx->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan transaksi ini?')">
                                        @csrf
                                        <button type="submit" 
                                            class="w-full sm:w-auto bg-white text-slate-500 border border-slate-200 px-6 py-3 rounded-xl font-bold text-sm hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition flex items-center justify-center gap-2">
                                            <i class="fa-solid fa-xmark"></i> {{ $isExpired ? 'Hapus' : 'Batalkan' }}
                                        </button>
                                    </form>

                                    @if(!$isExpired && $trx->snap_token)
                                        <button onclick="continuePayment('{{ $trx->snap_token }}')" 
                                            class="w-full sm:w-auto bg-emerald-500 text-slate-900 px-8 py-3 rounded-xl font-bold text-sm hover:bg-emerald-400 transition shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2 hover:-translate-y-0.5">
                                            <i class="fa-regular fa-credit-card"></i> Bayar
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div x-show="currentTab === 'riwayat'" x-transition.opacity style="display: none;">
                @if($historyBills->isEmpty())
                    <div class="text-center py-16">
                        <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center text-3xl mx-auto mb-4 border border-slate-100">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <h3 class="text-slate-800 font-bold text-lg">Belum Ada Riwayat</h3>
                        <p class="text-slate-500 text-sm mt-1">Anda belum pernah melakukan pembayaran yang berhasil.</p>
                    </div>
                @else
                    <div class="overflow-x-auto rounded-2xl border border-slate-200 shadow-sm">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Bulan Tagihan</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nominal</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Lunas</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100">
                                @foreach($historyBills as $bill)
                                <tr class="hover:bg-slate-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-bold text-slate-900">{{ $bill->month_name }}</div></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-mono text-slate-700 font-bold">Rp {{ number_format($bill->amount, 0, ',', '.') }}</div></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100"><i class="fa-solid fa-circle-check text-[10px]"></i> LUNAS</span></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-slate-400">{{ $bill->updated_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
        
        <div class="bg-slate-50 px-8 py-5 border-t border-slate-200/60 text-center md:text-right flex flex-col md:flex-row justify-between items-center gap-3">
            <span class="text-xs font-medium text-slate-400">&copy; {{ date('Y') }} KostPedia Finance</span>
            <span class="text-xs font-semibold text-slate-500 flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-full border border-slate-200 shadow-sm">
                <i class="fa-solid fa-shield-halved text-emerald-500"></i> Pembayaran Aman oleh Midtrans
            </span>
        </div>
    </div>
</div>

<script>
    function continuePayment(token) {
        if (typeof window.snap === 'undefined') {
            alert("Sistem pembayaran belum siap. Silakan refresh halaman.");
            return;
        }
        window.snap.pay(token, {
            onSuccess: function(result){ alert("Pembayaran Berhasil!"); location.reload(); },
            onPending: function(result){ alert("Status Pending..."); location.reload(); },
            onError: function(result){ alert("Pembayaran Gagal!"); location.reload(); },
            onClose: function(){ alert('Anda menutup popup.'); }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const checkboxes = document.querySelectorAll('.bill-checkbox');
        const totalDisplay = document.getElementById('total-display');
        const payButton = document.getElementById('pay-button');
        const loadingSpinner = document.getElementById('loading-spinner');
        const advanceInput = document.getElementById('advance_months');
        const pricePerMonth = parseFloat(document.getElementById('price_per_month').value || 0);

        window.selectAdvance = function(months) {
            if (months === 'all') {
                updateUI(0, true);
            } else {
                updateUI(parseInt(months), false);
            }
        };

        checkboxes.forEach((box, index) => {
            box.addEventListener('change', function() {
                advanceInput.value = 0; // Reset mode advance saat checkbox manual ditekan
                if(this.checked) {
                    for(let i = 0; i < index; i++) { checkboxes[i].checked = true; }
                } else {
                    for(let i = index + 1; i < checkboxes.length; i++) { checkboxes[i].checked = false; }
                }
                updateUI(0, false);
            });
        });

        function updateUI(advanceMonths = 0, selectAll = false) {
            let total = 0;
            let count = 0;

            if (selectAll) {
                advanceMonths = checkboxes.length;
            }

            if (advanceMonths > 0) {
                // Mode Pra-bayar: Kalkulasi berdasarkan harga sewa per bulan x jumlah bulan
                total = advanceMonths * pricePerMonth;
                count = advanceMonths;
                advanceInput.value = advanceMonths;

                checkboxes.forEach((box, i) => {
                    const row = document.getElementById('row-' + box.value);
                    if (i < advanceMonths) {
                        box.checked = true;
                        if(row) row.classList.add('border-emerald-400', 'bg-emerald-50/50', 'shadow-md');
                    } else {
                        box.checked = false;
                        if(row) row.classList.remove('border-emerald-400', 'bg-emerald-50/50', 'shadow-md');
                    }
                });
            } else {
                // Mode manual (pilih checkbox)
                advanceInput.value = 0;
                checkboxes.forEach(box => {
                    const row = document.getElementById('row-' + box.value);
                    if(box.checked) {
                        total += parseFloat(box.dataset.amount || 0);
                        count++;
                        if(row) row.classList.add('border-emerald-400', 'bg-emerald-50/50', 'shadow-md');
                    } else {
                        if(row) row.classList.remove('border-emerald-400', 'bg-emerald-50/50', 'shadow-md');
                    }
                });
            }

            totalDisplay.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);

            if(count > 0) {
                payButton.disabled = false;
                payButton.className = "w-full md:w-auto bg-emerald-500 text-slate-900 px-10 py-4 rounded-xl font-bold text-lg shadow-lg shadow-emerald-500/20 hover:bg-emerald-400 hover:-translate-y-1 flex items-center justify-center gap-3 transition-all duration-300 cursor-pointer";
            } else {
                payButton.disabled = true;
                payButton.className = "w-full md:w-auto bg-slate-200 text-slate-400 px-10 py-4 rounded-xl font-bold text-lg shadow-none flex items-center justify-center gap-3 transition-all duration-300 cursor-not-allowed";
            }
        }

        payButton?.addEventListener('click', async function() {
            if (typeof window.snap === 'undefined') {
                alert("Sistem pembayaran belum siap. Silakan refresh halaman.");
                return;
            }

            const originalText = payButton.querySelector('span').innerText;
            payButton.querySelector('span').innerText = 'Memproses...';
            payButton.disabled = true;
            loadingSpinner.classList.remove('hidden');

            const formData = new FormData(document.getElementById('payment-form'));

            try {
                const response = await fetch("{{ route('payment.process') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if(result.snap_token) {
                    window.snap.pay(result.snap_token, {
                        onSuccess: () => { alert("Pembayaran Berhasil!"); location.reload(); },
                        onPending: () => { alert("Menunggu Pembayaran..."); location.reload(); },
                        onError: () => { alert("Pembayaran Gagal!"); resetButton(originalText); },
                        onClose: () => { resetButton(originalText); }
                    });
                } else {
                    alert(result.error || 'Gagal generate token.');
                    resetButton(originalText);
                }
            } catch (error) {
                console.error(error);
                alert('Koneksi Gagal. Cek internet anda.');
                resetButton(originalText);
            }
        });

        function resetButton(text) {
            if(payButton) {
                payButton.querySelector('span').innerText = text;
                payButton.disabled = false;
                loadingSpinner.classList.add('hidden');
            }
        }
    });
</script>
@endsection