<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        
        <div id="global-loader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white/50 backdrop-blur-md transition-opacity duration-300">
            <div class="flex flex-col items-center gap-2">
                <div class="w-10 h-10 border-4 border-indigo-200 rounded-full animate-spin border-t-indigo-600"></div>
                <span class="text-xs font-medium text-slate-600 animate-pulse">Memuat...</span>
            </div>
        </div>
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const loader = document.getElementById('global-loader');

                window.addEventListener('load', function() {
                    // --- PENGATURAN WAKTU (DELAY) ---
                    // Ubah angka 1000 menjadi durasi yang diinginkan (dalam milidetik)
                    // 1000 = 1 detik
                    const delayWaktu = 1000; 

                    setTimeout(() => {
                        // Mulai hilangkan opacity (fade out)
                        loader.classList.add('opacity-0', 'pointer-events-none');
                        
                        // Tunggu transisi CSS selesai (300ms) baru hilangkan dari layar
                        setTimeout(() => {
                            loader.style.display = 'none';
                        }, 300);
                    }, delayWaktu);
                });

                // Tampilkan loader saat klik link (kecuali link tertentu)
                const links = document.querySelectorAll('a');
                links.forEach(link => {
                    link.addEventListener('click', function(e) {
                        const href = this.getAttribute('href');
                        const target = this.getAttribute('target');

                        // Cek kondisi agar tidak memunculkan loader pada link download/tab baru/#
                        if (href && href !== '#' && href.startsWith('http') && target !== '_blank' && !e.ctrlKey && !e.metaKey) {
                            loader.style.display = 'flex';
                            setTimeout(() => {
                                loader.classList.remove('opacity-0', 'pointer-events-none');
                            }, 10);
                        }
                    });
                });

                // Tampilkan loader saat submit form
                const forms = document.querySelectorAll('form');
                forms.forEach(form => {
                    form.addEventListener('submit', function() {
                        loader.style.display = 'flex';
                        setTimeout(() => {
                            loader.classList.remove('opacity-0', 'pointer-events-none');
                        }, 10);
                    });
                });
            });
        </script>
    </body>
</html>