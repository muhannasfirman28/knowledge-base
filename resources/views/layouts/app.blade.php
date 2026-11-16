<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kenyamanan Hunian Eksklusif')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#ecfdf5',  // emerald-50
                            100: '#d1fae5', // emerald-100
                            500: '#10b981', // emerald-500
                            600: '#059669', // emerald-600
                            900: '#064e3b', // emerald-900
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased flex flex-col min-h-screen">

    @php
        $menus = [
            [
                'label' => 'Beranda',
                'url'   => route('home'),
                'active' => request()->routeIs('home') && !request()->has('section'),
            ],
            [
                'label' => 'Syarat & Ketentuan',
                'url'   => route('terms'),
                'active' => request()->routeIs('terms'),
            ],
            [
                'label' => 'Tentang Kami',
                'url'   => route('about'),
                'active' => request()->routeIs('about'),
            ],
        ];
    @endphp

    <nav class="bg-white/80 backdrop-blur-xl sticky top-0 z-50 border-b border-slate-200/60 transition-all duration-300" x-data="{ mobileOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                
                <div class="flex-shrink-0 flex items-center gap-3 cursor-pointer group" onclick="window.location='{{ route('home') }}'">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-emerald-500/20 group-hover:rotate-12 group-hover:scale-105 transition-all duration-300">
                        <i class="fa-solid fa-house"></i>
                    </div>
                    <span class="font-bold text-2xl tracking-tight text-slate-900">Kost <span class="text-emerald-500">Ibu Zulfah</span></span>
                </div>

                <div class="hidden md:flex space-x-8 items-center">
                    @foreach($menus as $menu)
                        <a href="{{ $menu['url'] }}" 
                           class="text-sm font-semibold transition-colors duration-300 
                           {{ isset($menu['active']) && $menu['active'] ? 'text-emerald-600' : 'text-slate-500 hover:text-emerald-500' }}
                           ">
                            {{ $menu['label'] }}
                        </a>
                    @endforeach
                </div>

                <div class="md:hidden flex items-center">
                    <button @click="mobileOpen = !mobileOpen" class="text-slate-500 hover:text-emerald-600 focus:outline-none p-2">
                        <i class="fa-solid text-xl transition-transform" :class="mobileOpen ? 'fa-xmark rotate-90' : 'fa-bars'"></i>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden bg-white/95 backdrop-blur-xl border-b border-slate-200 shadow-2xl absolute w-full z-40"
             style="display: none;">
            <div class="px-4 pt-4 pb-6 space-y-2">
                @foreach($menus as $menu)
                    <a href="{{ $menu['url'] }}" 
                       class="block px-4 py-3 rounded-xl text-base font-semibold transition-colors
                       {{ isset($menu['active']) && $menu['active'] ? 'bg-emerald-50 text-emerald-600' : 'text-slate-600 hover:text-emerald-600 hover:bg-slate-50' }}">
                        {{ $menu['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-slate-200/60 mt-auto py-12">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-slate-900 rounded flex items-center justify-center text-white font-bold text-xs">K</div>
                <span class="font-bold text-slate-900 text-sm">Kost<span class="text-emerald-500">Pedia</span></span>
            </div>
            
            <p class="text-slate-400 text-sm font-medium">
                &copy; {{ date('Y') }} KostPedia Management. Hak cipta dilindungi.
            </p>
            
            <div class="flex gap-4 text-slate-400">
                <a href="#" class="hover:text-emerald-500 transition-colors"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="hover:text-emerald-500 transition-colors"><i class="fa-brands fa-whatsapp"></i></a>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>