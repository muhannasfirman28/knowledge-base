@extends('layouts.app')

@section('title', 'Kenyamanan Hunian Eksklusif')

@section('content')

<section class="relative bg-slate-900 text-white min-h-[70vh] flex items-center">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[20%] -right-[10%] w-[50%] h-[70%] rounded-full bg-emerald-600/20 blur-[120px]"></div>
        <div class="absolute bottom-[10%] -left-[10%] w-[40%] h-[50%] rounded-full bg-teal-800/30 blur-[100px]"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 w-full grid lg:grid-cols-2 gap-12 items-center py-20">
        
        <div class="space-y-6 text-left">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-emerald-400 text-sm font-medium">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Hunian Premium
            </div>
            <h1 class="text-5xl md:text-7xl font-bold tracking-tight leading-tight">
                Ruang <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-200">Privatmu.</span><br>
                Gaya Hidupmu.
            </h1>
            <p class="text-slate-400 text-lg max-w-md">
                Eksplorasi lokasi hunian strategis dengan desain modern dan fasilitas komprehensif.
            </p>
        </div>

        <div class="lg:justify-self-end w-full max-w-md">
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 p-8 rounded-3xl shadow-2xl">
                <h3 class="text-xl font-semibold mb-6 text-white">Portal Akses Penghuni</h3>
                
                <form action="{{ route('check') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <input type="text" name="tenancy_id" placeholder="ID Akses..." 
                               class="w-full bg-slate-800/50 border border-slate-700 text-white px-5 py-4 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all" required>
                    </div>
                    <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-slate-900 font-bold px-5 py-4 rounded-xl transition-colors duration-300 flex justify-center items-center gap-2">
                        Masuk Sistem <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    </button>
                </form>

                @error('tenancy_id')
                    <div class="mt-4 flex items-start gap-2 text-red-400 text-sm bg-red-400/10 p-3 rounded-lg border border-red-400/20">
                        <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                        <span>Akses ditolak. Periksa kembali ID Anda.</span>
                    </div>
                @enderror
            </div>
        </div>
    </div>
</section>

<section id="lokasi" class="py-24 bg-slate-50" 
         x-data="{ 
            drawerOpen: false, 
            roomData: { number: '', is_occupied: false, facilities: [] },
            groupName: ''
         }">
    
    <div class="max-w-7xl mx-auto px-4">
        
        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900">Eksplorasi Properti</h2>
                <p class="text-slate-500 mt-2">Pilih lokasi yang paling sesuai dengan aktivitasmu.</p>
            </div>
        </div>

        <div class="space-y-16">
            @foreach($groups as $group)
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200/60 overflow-hidden flex flex-col lg:flex-row">
                
                <div class="w-full lg:w-1/2 p-4 md:p-6">
                    @if($group->gallery && count($group->gallery) > 0)
                        <div class="grid grid-cols-3 gap-3 h-64 sm:h-80 lg:h-full min-h-[300px]">
                            <div class="col-span-2 h-full rounded-2xl overflow-hidden relative group">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($group->gallery[0]) }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-105" loading="lazy" alt="Foto Utama">
                            </div>
                            <div class="grid grid-rows-2 gap-3 h-full">
                                @if(isset($group->gallery[1]))
                                <div class="rounded-2xl overflow-hidden relative group">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($group->gallery[1]) }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-105" loading="lazy" alt="Foto 2">
                                </div>
                                @endif
                                @if(isset($group->gallery[2]))
                                <div class="rounded-2xl overflow-hidden relative group">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($group->gallery[2]) }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-105" loading="lazy" alt="Foto 3">
                                </div>
                                @elseif(count($group->gallery) > 1)
                                <div class="rounded-2xl bg-slate-100 flex items-center justify-center border border-slate-200">
                                    <span class="text-slate-400 text-sm font-medium">+ Lebih banyak</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="w-full h-64 lg:h-full bg-slate-100 rounded-2xl flex flex-col items-center justify-center text-slate-400 border-2 border-dashed border-slate-200">
                            <i class="fa-regular fa-image text-3xl mb-2"></i>
                            <span class="text-sm">Belum ada foto</span>
                        </div>
                    @endif
                </div>

                <div class="w-full lg:w-1/2 p-6 md:p-10 flex flex-col justify-center">
                    
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-slate-900 mb-2">{{ $group->name }}</h3>
                            <p class="text-slate-500 text-sm flex items-start gap-2">
                                <i class="fa-solid fa-location-dot mt-1 text-emerald-500"></i> {{ $group->address }}
                            </p>
                        </div>
                        <a href="{{ route('group.show', $group->id) }}" class="w-10 h-10 rounded-full bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-600 flex items-center justify-center transition flex-shrink-0">
                            <i class="fa-solid fa-arrow-right -rotate-45"></i>
                        </a>
                    </div>

                    <hr class="border-slate-100 my-6">

                    <div>
                        <h4 class="text-sm font-semibold text-slate-900 mb-4 uppercase tracking-wider">Ketersediaan Unit</h4>
                        
                        <div class="flex flex-wrap gap-3">
                            @foreach($group->rooms->take(8) as $room)
                                @php 
                                    $isOccupied = $room->activeTenancy()->exists();
                                    $roomJsData = [
                                        'number' => $room->number,
                                        'is_occupied' => $isOccupied,
                                        'facilities' => $room->facilities->map(function($f) {
                                            return ['name' => $f->name, 'icon' => $f->icon];
                                        })
                                    ];
                                @endphp

                                <button @click="roomData = {{ json_encode($roomJsData) }}; groupName = '{{ $group->name }}'; drawerOpen = true"
                                        class="px-4 py-2 rounded-xl text-sm font-medium border transition-all hover:-translate-y-0.5
                                        {{ $isOccupied ? 'bg-slate-50 border-slate-200 text-slate-400 opacity-60' : 'bg-white border-emerald-200 text-emerald-700 hover:shadow-md hover:border-emerald-400' }}">
                                    {{ $room->number }}
                                </button>
                            @endforeach

                            @if($group->rooms->count() > 8)
                            <a href="{{ route('group.show', $group->id) }}" class="px-4 py-2 rounded-xl text-sm font-medium border border-dashed border-slate-300 text-slate-500 hover:bg-slate-50 transition-all">
                                +{{ $group->rooms->count() - 8 }} Lainnya
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
            @endforeach
        </div>
    </div>

    <div class="relative z-[100]" aria-labelledby="slide-over-title" role="dialog" aria-modal="true" x-show="drawerOpen" style="display: none;">
        
        <div x-show="drawerOpen" 
             x-transition:enter="ease-in-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in-out duration-300" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" 
             @click="drawerOpen = false"></div>
      
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                    
                    <div x-show="drawerOpen" 
                         x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500" 
                         x-transition:enter-start="translate-x-full" 
                         x-transition:enter-end="translate-x-0" 
                         x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500" 
                         x-transition:leave-start="translate-x-0" 
                         x-transition:leave-end="translate-x-full" 
                         class="pointer-events-auto w-screen max-w-sm">
                        
                        <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-2xl rounded-l-[2rem]">
                            
                            <div class="px-6 py-8 bg-slate-50 border-b border-slate-100 relative">
                                <button @click="drawerOpen = false" class="absolute top-6 right-6 w-8 h-8 flex items-center justify-center rounded-full bg-white text-slate-400 hover:text-slate-600 shadow-sm border border-slate-200 transition">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                                
                                <p class="text-emerald-600 font-semibold text-sm mb-1" x-text="groupName"></p>
                                <h2 class="text-4xl font-extrabold text-slate-900" x-text="roomData.number"></h2>
                                
                                <div class="mt-4 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wide border"
                                     :class="roomData.is_occupied ? 'bg-slate-100 text-slate-500 border-slate-200' : 'bg-emerald-50 text-emerald-600 border-emerald-100'">
                                    <span class="w-2 h-2 rounded-full" :class="roomData.is_occupied ? 'bg-slate-400' : 'bg-emerald-500'"></span>
                                    <span x-text="roomData.is_occupied ? 'Tidak Tersedia' : 'Tersedia'"></span>
                                </div>
                            </div>

                            <div class="p-6 flex-1">
                                <h3 class="text-sm font-bold text-slate-900 mb-4 uppercase tracking-wider">Fasilitas Unit</h3>
                                
                                <template x-if="roomData.facilities.length > 0">
                                    <div class="grid grid-cols-2 gap-3">
                                        <template x-for="facility in roomData.facilities" :key="facility.name">
                                            <div class="bg-white border border-slate-200 p-3 rounded-xl flex flex-col gap-2">
                                                <div class="text-slate-400">
                                                    <template x-if="facility.icon"><i :class="facility.icon"></i></template>
                                                    <template x-if="!facility.icon"><i class="fa-solid fa-check text-emerald-500"></i></template>
                                                </div>
                                                <span class="text-xs font-semibold text-slate-700" x-text="facility.name"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <template x-if="roomData.facilities.length === 0">
                                    <div class="text-center py-10 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                        <p class="text-sm text-slate-400">Detail fasilitas belum diisi.</p>
                                    </div>
                                </template>
                            </div>
                            
                            <div class="p-6 border-t border-slate-100 bg-white">
                                <button @click="drawerOpen = false" class="w-full py-4 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                                    Tutup Panel
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

@endsection