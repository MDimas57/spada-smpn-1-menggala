{{-- File: resources/views/filament/admin/logo.blade.php --}}
<div class="flex items-center justify-start gap-3">
    {{-- 1. Gambar Logo --}}
    <img src="{{ asset('images/logo-sekolah.png') }}"
         alt="Logo Sekolah"
         class="w-auto h-10 rounded-full shadow-sm">

    {{-- 2. Teks di samping logo --}}
    {{-- Ubah text-center menjadi text-left agar teks rata kiri --}}
    <div class="text-lg leading-tight text-left text-gray-900 dark:text-white">
        <span class="block text-lg font-bold">SMP N 1 Menggala</span>
        <span class="block text-xs font-medium">Sistem Pembelajaran Dalam Jaringan</span>
    </div>
</div>
