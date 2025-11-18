<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Ketersediaan Kamar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center py-10 px-4">

    <div class="w-full max-w-4xl mb-10 text-center">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-2">Sistem Kost Digital</h1>
        <p class="text-gray-500 mb-8">Cek ketersediaan kamar atau bayar tagihan Anda di sini.</p>

        <div class="bg-white p-6 rounded-2xl shadow-lg max-w-lg mx-auto">
            <h2 class="text-lg font-semibold mb-4 text-gray-700">Penghuni Lama? Cek Tagihan</h2>
            <form action="{{ route('check') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="tenancy_id" placeholder="Masukkan Token ID (Contoh: 01J8X...)" 
                       class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-indigo-700 transition">
                    Masuk
                </button>
            </form>
            @error('tenancy_id')
                <p class="text-red-500 text-sm mt-2 text-left">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="w-full max-w-6xl space-y-8">
        @foreach($groups as $group)
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6 border-b pb-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $group->name }}</h3>
                        <p class="text-gray-500 text-sm">{{ $group->address }}</p>
                    </div>
                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold">
                        {{ $group->rooms->count() }} Kamar
                    </span>
                </div>
                
                <div class="grid grid-cols-3 md:grid-cols-6 lg:grid-cols-8 gap-4">
                    @foreach($group->rooms as $room)
                        @php
                            // Cek Logic: Punya activeTenancy?
                            $isOccupied = $room->activeTenancy()->exists();
                        @endphp

                        <div class="group relative p-4 rounded-xl text-center border-2 transition duration-300 cursor-default
                            {{ $isOccupied 
                                ? 'bg-red-50 border-red-200 text-red-600' 
                                : 'bg-emerald-50 border-emerald-200 text-emerald-600 hover:-translate-y-1 hover:shadow-md' 
                            }}">
                            
                            <div class="text-3xl mb-2">
                                <i class="fa-solid {{ $isOccupied ? 'fa-user-lock' : 'fa-door-open' }}"></i>
                            </div>
                            <div class="font-bold text-lg">{{ $room->number }}</div>
                            <div class="text-[10px] uppercase tracking-wider font-bold mt-1">
                                {{ $isOccupied ? 'TERISI' : 'KOSONG' }}
                            </div>

                            @if(!$isOccupied)
                                <div class="absolute inset-0 bg-emerald-600 bg-opacity-90 text-white rounded-xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                                    <span class="text-xs font-bold">Tersedia</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

</body>
</html>