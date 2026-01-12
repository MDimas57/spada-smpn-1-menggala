{{-- File: resources/views/filament/admin/logo.blade.php --}}
<div class="flex items-center justify-center gap-3"> 
    {{-- 1. Gambar Logo --}}
    {{-- Class 'h-10' (40px) atau 'h-12' (48px) sudah cukup ideal untuk layout samping --}}
    <img src="{{ asset('images/logo-sekolah.png') }}" 
         alt="Logo Sekolah" 
         class="h-10 w-auto shadow-sm rounded-full"> 

    {{-- 2. Teks di samping logo --}}
    {{-- text-left agar rata kiri relatif terhadap logo --}}
    <div class="font-bold text-lg leading-tight text-gray-900 dark:text-white text-center">
        Spada<br>
        SMP N 1 Menggala
    </div>
</div>