<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Akses - {{ $tenancy->room->number }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>

    <style>
        /* Pengaturan Khusus Kertas & Printer */
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; margin: 0; padding: 0; }
            .print-card { 
                box-shadow: none !important; 
                border: 2px solid #cbd5e1 !important; /* Border tegas saat diprint */
                margin: 0 auto;
            }
            * { -webkit-print-color-adjust: exact !important; color-adjust: exact !important; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4 font-sans">

    <div class="print-card bg-white p-8 rounded-[2rem] border border-slate-200 w-[350px] text-center shadow-2xl relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-400 to-teal-500"></div>

        <div class="mb-6 mt-2">
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Kost<span class="text-emerald-500">Pedia</span></h1>
            <p class="text-[10px] text-slate-400 uppercase tracking-widest mt-1 font-semibold">Kartu Akses Pembayaran</p>
        </div>

        <div class="border-y border-dashed border-slate-200 py-6 mb-6">
            <h2 class="text-5xl font-black text-slate-900 tracking-tighter">{{ $tenancy->room->number }}</h2>
            <p class="text-sm text-slate-500 mt-2 font-medium">{{ $tenancy->room->group->name }}</p>
            <div class="mt-4 inline-block bg-emerald-50 px-4 py-1.5 rounded-full text-xs font-bold text-emerald-700 border border-emerald-100">
                {{ $tenancy->mainTenant->name ?? 'Penghuni' }}
            </div>
        </div>

        <div class="flex justify-center mb-6">
            <div class="p-2 bg-white rounded-2xl border border-slate-100 shadow-sm">
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(180)->generate(route('payment.show', $tenancy->id)) !!}
            </div>
        </div>

        <div class="text-xs text-slate-400">
            <p class="mb-1.5 font-medium">Scan QR code ini untuk melihat tagihan</p>
            <p class="font-mono font-bold text-slate-600 tracking-wide">ID: {{ $tenancy->id }}</p>
        </div>

        <div class="absolute bottom-4 right-4 opacity-10 text-[10px] font-bold">
            KP - SYSTEM
        </div>
    </div>

    <button onclick="window.print()" class="no-print fixed bottom-10 right-10 bg-slate-900 text-white p-4 rounded-full shadow-xl hover:bg-slate-800 hover:-translate-y-1 transition-all duration-300 group flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
    </button>

    <script>
        // Otomatis muncul dialog print saat halaman dibuka
        window.onload = function() {
            setTimeout(function(){ window.print(); }, 800);
        }
    </script>
</body>
</html>