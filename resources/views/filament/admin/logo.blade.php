{{-- File: resources/views/filament/admin/logo.blade.php --}}
<div class="flex items-center justify-start gap-3"> 
    {{-- 1. Gambar Logo --}}
    <img src="{{ asset('images/logo-sekolah.png') }}" 
         alt="Logo Sekolah" 
         class="h-10 w-auto shadow-sm rounded-full"> 

    {{-- 2. Teks di samping logo --}}
    {{-- Ubah text-center menjadi text-left agar teks rata kiri --}}
    <div class="font-bold text-lg leading-tight text-gray-900 dark:text-white text-center">
        Spada<br>
        SMP N 1 Menggala
    </div>
</div>