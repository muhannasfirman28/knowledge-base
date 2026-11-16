@extends('layouts.app')

@section('title', 'Tentang Kami - KostPedia')

@section('content')

<div class="bg-white py-20 border-b border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 text-sm font-bold mb-6 tracking-wide">
            <i class="fa-solid fa-building-user"></i> TENTANG KAMI
        </div>
        <h1 class="text-4xl md:text-6xl font-extrabold text-slate-900 mb-6 tracking-tight">
            Menciptakan Ruang <br>Untuk <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-600">Berkembang.</span>
        </h1>
        <p class="text-xl text-slate-500 max-w-3xl mx-auto leading-relaxed">
            KostPedia hadir bukan sekadar tempat beristirahat, tapi sebagai ekosistem hunian modern yang mendukung produktivitas, privasi, dan ketenangan Anda.
        </p>
    </div>
</div>

<div class="bg-slate-50 py-24 relative overflow-hidden">
    <div class="absolute top-0 left-0 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
    
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            
            <div class="relative group">
                <div class="absolute inset-0 bg-gradient-to-tr from-emerald-400 to-teal-600 rounded-[2rem] transform rotate-3 scale-105 opacity-20 group-hover:rotate-6 transition-transform duration-500"></div>
                <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OHx8dmlsbGF8ZW58MHx8MHx8fDA%3D" 
                     alt="Team Work" 
                     class="relative rounded-[2rem] shadow-2xl w-full object-cover h-[400px] lg:h-[500px] border border-white/50 z-10 transition-transform duration-500 group-hover:-translate-y-2">
            </div>
            
            <div class="space-y-8">
                <div>
                    <h3 class="text-3xl font-bold text-slate-900 mb-4">Visi Kami</h3>
                    <p class="text-slate-600 leading-relaxed text-lg">
                        Kami memahami betapa sulitnya mencari tempat tinggal yang bersih, aman, dan terjangkau di tengah mobilitas yang tinggi. Oleh karena itu, kami membangun manajemen properti yang terintegrasi penuh dengan teknologi.
                    </p>
                </div>
                
                <div class="space-y-5">
                    <div class="flex items-start gap-4 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                            <i class="fa-solid fa-broom text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 mb-1">Standar Kebersihan Tinggi</h4>
                            <p class="text-sm text-slate-500">Pemeliharaan area publik dan layanan housekeeping rutin untuk kenyamanan maksimal.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                            <i class="fa-solid fa-shield-halved text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 mb-1">Keamanan Komprehensif</h4>
                            <p class="text-sm text-slate-500">Akses privat, pengawasan CCTV 24 jam, dan lingkungan yang tersortir dengan baik.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                            <i class="fa-solid fa-wifi text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 mb-1">Konektivitas Stabil</h4>
                            <p class="text-sm text-slate-500">Jaringan internet high-speed di seluruh area untuk menunjang WFH dan produktivitas digital.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="bg-slate-900 py-20 relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5"></div>
    <div class="max-w-7xl mx-auto px-4 relative z-10 grid grid-cols-2 md:grid-cols-4 gap-10 text-center divide-x divide-slate-800">
        
        <div class="px-4">
            <div class="text-4xl md:text-5xl font-extrabold text-white mb-2">5<span class="text-emerald-500">+</span></div>
            <div class="text-slate-400 text-xs md:text-sm uppercase tracking-widest font-semibold">Lokasi Strategis</div>
        </div>
        
        <div class="px-4">
            <div class="text-4xl md:text-5xl font-extrabold text-white mb-2">100<span class="text-emerald-500">+</span></div>
            <div class="text-slate-400 text-xs md:text-sm uppercase tracking-widest font-semibold">Kamar Eksklusif</div>
        </div>
        
        <div class="px-4">
            <div class="text-4xl md:text-5xl font-extrabold text-white mb-2">500<span class="text-emerald-500">+</span></div>
            <div class="text-slate-400 text-xs md:text-sm uppercase tracking-widest font-semibold">Penghuni Aktif</div>
        </div>
        
        <div class="px-4">
            <div class="text-4xl md:text-5xl font-extrabold text-white mb-2">24<span class="text-emerald-500">/7</span></div>
            <div class="text-slate-400 text-xs md:text-sm uppercase tracking-widest font-semibold">Dukungan Sistem</div>
        </div>
        
    </div>
</div> --}}

<div class="py-24 px-4 bg-white">
    <div class="max-w-4xl mx-auto bg-slate-900 rounded-[2rem] p-12 text-center shadow-2xl relative overflow-hidden">
        
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-teal-500/20 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4 tracking-tight">Siap untuk memulai transisi?</h2>
            <p class="text-slate-400 mb-10 text-lg max-w-lg mx-auto">
                Eksplorasi ketersediaan unit hari ini dan rasakan pengalaman hunian yang berbeda.
            </p>
            <a href="{{ route('home') }}#lokasi" class="inline-flex items-center gap-2 bg-emerald-500 text-slate-900 font-bold px-8 py-4 rounded-xl hover:bg-emerald-400 transition-all shadow-lg shadow-emerald-500/20 hover:-translate-y-1">
                Eksplorasi Properti <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
</div>

@endsection