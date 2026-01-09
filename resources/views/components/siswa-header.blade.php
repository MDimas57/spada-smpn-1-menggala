@props(['active' => null])

<div class="bg-white shadow">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Left side - Welcome text -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Halo, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="mt-1 text-sm text-gray-600">
                    Kelas {{ Auth::user()->siswa->kelas->nama ?? '-' }}
                </p>
            </div>

            <!-- Right side - Navigation tabs -->
            <div class="flex items-center gap-2">
                <!-- Dashboard Tab -->
                <a href="{{ route('siswa.dashboard') }}"
                   class="px-6 py-3 rounded-lg font-medium transition-all duration-200 {{ request()->routeIs('siswa.dashboard') ? 'bg-blue-100 text-blue-700 border-b-4 border-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    Dashboard
                </a>

                <!-- My Courses Tab -->
                <a href="{{ route('siswa.my-courses') }}"
                   class="px-6 py-3 rounded-lg font-medium transition-all duration-200 {{ request()->routeIs('siswa.my-courses') ? 'bg-blue-100 text-blue-700 border-b-4 border-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    My Courses
                </a>
            </div>
        </div>
    </div>
</div>
