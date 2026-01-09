{{-- Logo Sekolah - Ganti dengan gambar logo sekolah Anda --}}
@if(file_exists(public_path('images/logo-sekolah.png')))
    <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo Sekolah" class="h-full" style="max-height: 40px;">
@elseif(file_exists(public_path('images/logo-sekolah.jpg')))
    <img src="{{ asset('images/logo-sekolah.jpg') }}" alt="Logo Sekolah" class="h-full" style="max-height: 40px;">
@else
    {{-- Fallback: Placeholder jika logo belum ada --}}
    <div class="h-10 w-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-lg">
        📚
    </div>
@endif
