<x-app-layout>
    <div class="min-h-screen py-12 bg-gray-50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <div class="mb-6 lg:hidden">
                <h2 class="text-2xl font-bold text-gray-800">Pengaturan Akun</h2>
                <p class="text-sm text-gray-500">Kelola profil dan keamanan akun Anda.</p>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

                <div class="lg:col-span-1">
                    <div class="sticky space-y-6 top-24">

                        <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
                            <div class="h-32 bg-gradient-to-r from-teal-500 to-cyan-600"></div>

                            <div class="relative px-6 pb-6 text-center">
                                <div class="relative inline-block -mt-16">
                                    <div class="flex items-center justify-center w-32 h-32 text-4xl font-bold text-white bg-teal-600 border-4 border-white rounded-full shadow-md">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <div class="absolute w-6 h-6 border-4 border-white rounded-full bottom-2 right-2 bg-emerald-500" title="Online"></div>
                                </div>

                                <div class="mt-4">
                                    <h3 class="text-xl font-bold text-gray-800">{{ Auth::user()->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>

                                    @if(Auth::user()->siswa)
                                        <div class="inline-flex items-center px-3 py-1 mt-4 text-xs font-medium border rounded-full text-cyan-700 bg-cyan-50 border-cyan-100">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            Kelas {{ Auth::user()->siswa->kelas->nama ?? '-' }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="hidden overflow-hidden bg-white border border-gray-200 shadow-sm lg:block rounded-2xl">
                            <nav class="flex flex-col">
                                <a href="#profile-info" class="flex items-center px-6 py-4 text-sm font-medium text-teal-700 transition-colors border-l-4 border-teal-500 bg-teal-50">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Informasi Pribadi
                                </a>
                                <a href="#security" class="flex items-center px-6 py-4 text-sm font-medium text-gray-600 transition-colors border-l-4 border-transparent hover:bg-gray-50 hover:border-gray-300">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    Keamanan & Password
                                </a>
                            </nav>
                        </div>

                    </div>
                </div>

                <div class="space-y-8 lg:col-span-2">

                    <div id="profile-info" class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
                        <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                            <div class="flex items-center justify-center w-8 h-8 text-teal-600 bg-white border border-gray-200 rounded-lg shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Informasi Pribadi</h3>
                                <p class="text-xs text-gray-500">Perbarui nama profil dan alamat email akun Anda.</p>
                            </div>
                        </div>
                        <div class="p-6 md:p-8">
                            <div class="max-w-xl">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>
                    </div>

                    <div id="security" class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
                        <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                            <div class="flex items-center justify-center w-8 h-8 text-orange-600 bg-white border border-gray-200 rounded-lg shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Keamanan Akun</h3>
                                <p class="text-xs text-gray-500">Pastikan akun Anda aman dengan password yang kuat.</p>
                            </div>
                        </div>
                        <div class="p-6 md:p-8">
                            <div class="max-w-xl">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                    </div>

                    {{--
                    <div class="overflow-hidden bg-white border border-red-100 shadow-sm rounded-2xl">
                        <div class="flex items-center gap-3 px-6 py-5 border-b border-red-50 bg-red-50/30">
                            <div class="flex items-center justify-center w-8 h-8 text-red-600 bg-white border border-red-100 rounded-lg shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-red-700">Hapus Akun</h3>
                                <p class="text-xs text-red-500">Tindakan ini permanen dan tidak dapat dibatalkan.</p>
                            </div>
                        </div>
                        <div class="p-6 md:p-8">
                            <div class="max-w-xl">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>
                    --}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
