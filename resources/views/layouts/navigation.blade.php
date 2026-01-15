<nav x-data="{ open: false }" class="sticky top-0 z-50 transition-all duration-300 border-b border-gray-200 shadow-sm bg-white/90 backdrop-blur-md">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center gap-3 sm:gap-8">
                <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                    <a href="{{ route('dashboard') }}" class="group">
                        <x-application-logo class="block w-auto h-8 text-teal-600 transition-transform duration-200 fill-current sm:h-10 group-hover:scale-105" />
                    </a>
                    <div>
                        <div class="text-xs font-bold text-gray-900 sm:text-sm">
                            SMP Negeri 1 Menggala
                        </div>
                        <div class="text-xs text-gray-500">
                            Sistem Pembelajaran Dalam Jaringan
                        </div>
                    </div>
                </div>

                <div class="hidden space-x-2 sm:flex">
                    @if(Auth::check() && Auth::user()->hasRole('siswa'))
                        <a href="{{ route('siswa.dashboard') }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('siswa.dashboard') ? 'bg-teal-600 text-white shadow-md shadow-teal-200' : 'text-gray-600 hover:bg-gray-100 hover:text-teal-700' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('siswa.my-courses') }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('siswa.my-courses') ? 'bg-teal-600 text-white shadow-md shadow-teal-200' : 'text-gray-600 hover:bg-gray-100 hover:text-teal-700' }}">
                            My Courses
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-teal-600 text-white shadow-md shadow-teal-200' : 'text-gray-600 hover:bg-gray-100 hover:text-teal-700' }}">
                            {{ __('Dashboard') }}
                        </a>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-1 py-1 text-sm font-medium leading-4 text-gray-600 transition duration-200 ease-in-out border border-transparent border-gray-200 rounded-full bg-gray-50 hover:bg-white hover:shadow-md hover:text-teal-600 group">

                            <div class="flex items-center justify-center w-8 h-8 mr-2 font-bold text-teal-600 transition-colors bg-teal-100 rounded-full group-hover:bg-teal-600 group-hover:text-white">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>

                            <span class="mr-1">{{ Auth::user()->name }}</span>

                            <div class="mr-2">
                                <svg class="w-4 h-4 text-gray-400 fill-current group-hover:text-teal-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Manage Account') }}
                        </div>

                        <x-dropdown-link :href="route('profile.edit')" class="hover:bg-teal-50 hover:text-teal-600">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <div class="border-t border-gray-100"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-red-600 hover:bg-red-50 hover:text-red-700">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="flex items-center -me-2 sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md hover:text-teal-600 hover:bg-teal-50 focus:outline-none focus:bg-teal-50 focus:text-teal-600">
                    <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden bg-white border-t border-gray-200 shadow-lg sm:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1">
            @if(Auth::check() && Auth::user()->hasRole('siswa'))
                <x-responsive-nav-link :href="route('siswa.dashboard')" :active="request()->routeIs('siswa.dashboard')"
                    class="{{ request()->routeIs('siswa.dashboard') ? 'bg-teal-50 text-teal-700 border-teal-500' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }} rounded-md border-l-4">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('siswa.my-courses')" :active="request()->routeIs('siswa.my-courses')"
                    class="{{ request()->routeIs('siswa.my-courses') ? 'bg-teal-50 text-teal-700 border-teal-500' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }} rounded-md border-l-4">
                    {{ __('My Courses') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                    class="{{ request()->routeIs('dashboard') ? 'bg-teal-50 text-teal-700 border-teal-500' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }} rounded-md border-l-4">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center gap-3 px-4">
                <div class="flex-shrink-0">
                      <div class="flex items-center justify-center w-10 h-10 text-lg font-bold text-teal-600 bg-teal-100 rounded-full">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
                <div>
                    <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="px-2 mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="rounded-md hover:bg-white hover:shadow-sm hover:text-teal-600">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-red-600 rounded-md hover:bg-red-50">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
