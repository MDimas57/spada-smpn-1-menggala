<x-app-layout>
    <div class="min-h-screen py-12 bg-slate-50/50">
        <div class="max-w-4xl px-4 mx-auto space-y-8 sm:px-6 lg:px-8">

            <div class="flex flex-col gap-6 mb-8 md:flex-row md:items-center">
                <div class="relative">
                    <div class="flex items-center justify-center w-20 h-20 text-3xl font-bold text-white bg-indigo-600 rounded-full shadow-lg shadow-indigo-200 ring-4 ring-white">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="absolute bottom-0 right-0 w-6 h-6 border-2 border-white rounded-full bg-emerald-500" title="Online"></div>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Pengaturan Profil</h2>
                    <p class="text-slate-500">
                        Kelola informasi akun, keamanan, dan preferensi Anda.
                    </p>
                    @if(Auth::user()->siswa)
                        <div class="inline-flex items-center px-3 py-1 mt-2 text-xs font-medium text-blue-700 border border-blue-100 rounded-full bg-blue-50">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Kelas {{ Auth::user()->siswa->kelas->nama ?? '-' }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="p-6 bg-white border shadow-sm sm:p-8 sm:rounded-2xl border-slate-200">
                <div class="max-w-xl">
                    <div class="flex items-start gap-4 pb-6 mb-6 border-b border-slate-100">
                        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 text-blue-600 rounded-lg bg-blue-50">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Informasi Pribadi</h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Perbarui informasi profil akun dan alamat email Anda.
                            </p>
                        </div>
                    </div>

                    <div class="mt-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white border shadow-sm sm:p-8 sm:rounded-2xl border-slate-200">
                <div class="max-w-xl">
                    <div class="flex items-start gap-4 pb-6 mb-6 border-b border-slate-100">
                        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-lg bg-amber-50 text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Keamanan Akun</h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Pastikan akun Anda aman dengan menggunakan password yang kuat.
                            </p>
                        </div>
                    </div>

                    <div class="mt-6">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            {{--
            <div class="p-6 bg-white border shadow-sm sm:p-8 sm:rounded-2xl border-slate-200">
                <div class="max-w-xl">
                    <div class="flex items-start gap-4 pb-6 mb-6 border-b border-slate-100">
                        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 text-red-600 rounded-lg bg-red-50">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-red-600">Hapus Akun</h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Tindakan ini tidak dapat dibatalkan. Semua data akan hilang permanen.
                            </p>
                        </div>
                    </div>
                    <div class="mt-6">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
            --}}

        </div>
    </div>
</x-app-layout>
