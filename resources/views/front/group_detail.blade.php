@extends('layouts.app')

@section('title', $group->name . ' - Detail Kost')

@section('content')
<div class="bg-gray-50 min-h-screen pb-20">
    
    <!-- HEADER IMAGE / GALLERY SLIDER -->
    <div class="relative bg-gray-900 h-[40vh] md:h-[50vh] overflow-hidden">
        @if($group->gallery && count($group->gallery) > 0)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($group->gallery[0]) }}" class="w-full h-full object-cover opacity-60" alt="Cover">
        @else
            <div class="w-full h-full bg-brand-900 opacity-80"></div>
        @endif
        
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent"></div>
        
        <div class="absolute bottom-0 left-0 right-0 p-6 md:p-10 max-w-7xl mx-auto">
            <a href="{{ route('home') }}" class="inline-flex items-center text-white/80 hover:text-white mb-4 transition text-sm">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Beranda
            </a>
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-2">{{ $group->name }}</h1>
            <p class="text-white/90 text-lg flex items-center gap-2">
                <i class="fa-solid fa-map-pin text-brand-400"></i> {{ $group->address }}
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 -mt-10 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- KOLOM KIRI: INFO UTAMA & DENAH -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- 1. SEMUA KAMAR (DENGAN MODAL) -->
                <div class="bg-white rounded-3xl shadow-lg p-6 md:p-8" 
                     x-data="{ 
                        roomModalOpen: false, 
                        selectedRoom: { number: '', is_occupied: false, facilities: [] } 
                     }">
                    
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-border-all text-brand-600"></i> Ketersediaan Unit
                        </h3>
                        <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold">
                            Total {{ $group->rooms->count() }} Kamar
                        </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($group->rooms as $room)
                            @php 
                                $isOccupied = $room->activeTenancy()->exists(); 
                                // Siapkan data JS untuk Modal
                                $roomJsData = [
                                    'number' => $room->number,
                                    'is_occupied' => $isOccupied,
                                    'facilities' => $room->facilities->map(function($f) {
                                        return [
                                            'name' => $f->name,
                                            'icon' => $f->icon
                                        ];
                                    })
                                ];
                            @endphp
                            
                            <!-- ROOM CARD (CLICKABLE) -->
                            <div @click="selectedRoom = {{ json_encode($roomJsData) }}; roomModalOpen = true"
                                 class="relative group p-4 rounded-xl border transition-all text-center cursor-pointer
                                 {{ $isOccupied 
                                    ? 'bg-gray-50 border-gray-100 opacity-80' 
                                    : 'bg-white border-brand-200 shadow-sm hover:ring-2 hover:ring-brand-500 hover:-translate-y-1' 
                                 }}">
                                
                                <div class="text-3xl mb-2 {{ $isOccupied ? 'text-gray-300' : 'text-brand-500' }}">
                                    <i class="fa-solid fa-door-closed"></i>
                                </div>
                                <div class="font-bold text-gray-800">{{ $room->number }}</div>
                                <div class="text-[10px] uppercase font-bold mt-1 {{ $isOccupied ? 'text-red-500' : 'text-green-600' }}">
                                    {{ $isOccupied ? 'Terisi' : 'Tersedia' }}
                                </div>

                                <!-- Hint Text -->
                                <div class="mt-2 text-[10px] text-gray-400 group-hover:text-brand-500 transition">
                                    Lihat Fasilitas
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- =========================== -->
                    <!-- MODAL DETAIL KAMAR (POP-UP) -->
                    <!-- =========================== -->
                    <div x-show="roomModalOpen" style="display: none;" 
                         class="fixed inset-0 z-[99] flex items-center justify-center px-4"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0" 
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100" 
                         x-transition:leave-end="opacity-0">
                        
                        <!-- Backdrop -->
                        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="roomModalOpen = false"></div>

                        <!-- Modal Content -->
                        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm relative z-10 overflow-hidden transform transition-all"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                            
                            <!-- Header Modal -->
                            <div class="bg-brand-600 p-6 text-white text-center relative">
                                <button @click="roomModalOpen = false" class="absolute top-4 right-4 text-white/70 hover:text-white transition">
                                    <i class="fa-solid fa-xmark text-xl"></i>
                                </button>
                                <h3 class="text-3xl font-bold" x-text="selectedRoom.number"></h3>
                                <p class="text-brand-100 text-sm opacity-90">{{ $group->name }}</p>
                                
                                <div class="mt-3 inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/20 backdrop-blur-md"
                                     :class="selectedRoom.is_occupied ? 'text-red-100 bg-red-500/50' : 'text-green-100 bg-green-500/50'">
                                    <span x-text="selectedRoom.is_occupied ? 'Sedang Terisi' : 'Siap Huni'"></span>
                                </div>
                            </div>

                            <!-- Body Modal -->
                            <div class="p-6">
                                <h4 class="text-gray-800 font-bold mb-4 flex items-center gap-2">
                                    <i class="fa-solid fa-list-check text-brand-600"></i> Fasilitas Kamar
                                </h4>
                                
                                <template x-if="selectedRoom.facilities && selectedRoom.facilities.length > 0">
                                    <ul class="space-y-3">
                                        <template x-for="facility in selectedRoom.facilities" :key="facility.name">
                                            <li class="flex items-center gap-3 text-gray-600 bg-gray-50 p-3 rounded-xl border border-gray-100">
                                                <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center text-brand-500 shadow-sm">
                                                    <template x-if="facility.icon">
                                                        <i :class="facility.icon"></i>
                                                    </template>
                                                    <template x-if="!facility.icon">
                                                        <span class="font-bold text-xs" x-text="facility.name.substring(0,1)"></span>
                                                    </template>
                                                </div>
                                                <span x-text="facility.name" class="font-medium text-sm"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </template>

                                <template x-if="!selectedRoom.facilities || selectedRoom.facilities.length === 0">
                                    <div class="text-center py-6 text-gray-400">
                                        <i class="fa-regular fa-circle-question text-3xl mb-2"></i>
                                        <p class="text-sm">Belum ada data fasilitas.</p>
                                    </div>
                                </template>

                                <div class="mt-8">
                                    <button @click="roomModalOpen = false" class="w-full bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END MODAL -->

                </div>

                <!-- 2. GALERI FOTO LENGKAP -->
                @if($group->gallery && count($group->gallery) > 0)
                <div class="bg-white rounded-3xl shadow-lg p-6 md:p-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-images text-brand-600"></i> Galeri Foto
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($group->gallery as $image)
                            <div class="aspect-video rounded-xl overflow-hidden shadow-sm group relative">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($image) }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Galeri">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition duration-300"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            <!-- KOLOM KANAN: PETA & KONTAK -->
            <div class="space-y-8">
                <!-- MAPS -->
                <div class="bg-white rounded-3xl shadow-lg p-2 overflow-hidden">
                    <div class="h-[300px] w-full rounded-2xl overflow-hidden bg-gray-100 relative">
                        @if($group->google_maps_embed)
                            <div class="w-full h-full [&>iframe]:w-full [&>iframe]:h-full">
                                {!! $group->google_maps_embed !!}
                            </div>
                        @else
                            <div class="absolute inset-0 flex items-center justify-center text-gray-400 flex-col">
                                <i class="fa-regular fa-map text-4xl mb-2"></i>
                                <span>Peta tidak tersedia</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <a href="https://maps.google.com/?q={{ urlencode($group->address) }}" target="_blank" class="block w-full text-center bg-brand-50 text-brand-700 font-bold py-3 rounded-xl hover:bg-brand-100 transition">
                            <i class="fa-solid fa-location-arrow mr-2"></i> Buka di Google Maps
                        </a>
                    </div>
                </div>

                <!-- INFO KONTAK -->
                <div class="bg-brand-900 rounded-3xl shadow-lg p-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
                    <h3 class="text-xl font-bold mb-4">Butuh Bantuan?</h3>
                    <p class="text-brand-100 mb-6 text-sm">Jika Anda berminat atau ingin menanyakan ketersediaan, hubungi admin kami.</p>
                    <a href="https://wa.me/{{ env('PHONE_WA') }}" target="_blank" class="flex items-center justify-center gap-3 w-full bg-green-500 text-white font-bold py-4 rounded-xl hover:bg-green-600 transition shadow-lg shadow-green-500/30">
                        <i class="fa-brands fa-whatsapp text-2xl"></i> Hubungi via WhatsApp
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection