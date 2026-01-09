<x-app-layout>
    <div class="min-h-screen py-12 bg-slate-50/50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('siswa.my-courses') }}"
                            class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-indigo-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                </path>
                            </svg>
                            My Courses
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-slate-500 md:ml-2">{{ $course->nama }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Course Header with Banner -->
            <div class="mb-8 overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
                @php
                    $headerGradient = 'from-indigo-600 to-violet-700';
                @endphp

                <div class="relative p-8 bg-gradient-to-br {{ $headerGradient }}">
                    <!-- Hexagon Pattern -->
                    <div class="absolute inset-0 opacity-20">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="header-hexagons" x="0" y="0" width="56" height="100"
                                    patternUnits="userSpaceOnUse">
                                    <polygon points="28,0 56,16.66 56,50 28,66.66 0,50 0,16.66" fill="white"
                                        fill-opacity="0.3" />
                                    <polygon points="28,66.66 56,83.32 56,116.66 28,133.32 0,116.66 0,83.32"
                                        fill="white" fill-opacity="0.3" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#header-hexagons)" />
                        </svg>
                    </div>

                    <div class="absolute top-0 right-0 w-64 h-64 -mt-16 -mr-16 rounded-full bg-white/10 blur-3xl"></div>

                    <div class="relative">
                        <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
                            <div class="flex-1">
                                <div
                                    class="inline-flex items-center px-3 py-1 mb-3 text-xs font-bold tracking-wider text-white uppercase bg-white rounded-full bg-opacity-30 backdrop-blur-sm">
                                    {{ $course->mapel->nama ?? 'Mata Pelajaran' }}
                                </div>
                                <h1 class="mb-2 text-3xl font-bold text-white">{{ $course->nama }}</h1>
                                <p class="max-w-2xl text-indigo-100">
                                    {!! strip_tags($course->deskripsi) ?:
                                        'Selamat datang di course ini! Jelajahi semua modul pembelajaran yang tersedia.' !!}
                                </p>
                                <div class="flex items-center gap-3 mt-4">
                                    <div class="flex items-center gap-2 text-white">
                                        <div
                                            class="flex items-center justify-center w-8 h-8 border border-white rounded-full bg-white/20">
                                            {{ substr($course->guru->user->name ?? 'G', 0, 1) }}
                                        </div>
                                        <span
                                            class="text-sm font-medium">{{ $course->guru->user->name ?? 'Guru' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <div class="border-b border-slate-200 bg-slate-50/50">
                    <nav class="flex px-6 -mb-px space-x-8" aria-label="Tabs">
                        <a href="{{ route('siswa.course.show', $course->id) }}"
                            class="px-1 py-4 text-sm font-medium transition-colors border-b-2 border-transparent text-slate-600 hover:text-slate-800 hover:border-slate-300 whitespace-nowrap">
                            Course
                        </a>
                        <a href="{{ route('siswa.course.participants', $course->id) }}"
                            class="px-1 py-4 text-sm font-semibold text-indigo-600 transition-colors border-b-2 border-indigo-600 whitespace-nowrap">
                            Participants
                        </a>
                        <a href="{{ route('siswa.course.grades', $course->id) }}"
                            class="px-1 py-4 text-sm font-medium transition-colors border-b-2 border-transparent text-slate-600 hover:text-slate-800 hover:border-slate-300 whitespace-nowrap">
                            Grades
                        </a>
                        <a href="#"
                            class="px-1 py-4 text-sm font-medium transition-colors border-b-2 border-transparent text-slate-600 hover:text-slate-800 hover:border-slate-300 whitespace-nowrap">
                            Competencies
                        </a>
                        {{-- <a href="{{ route('siswa.course.badges', $course->id) }}"
                            class="px-1 py-4 text-sm font-medium transition-colors border-b-2 border-transparent text-slate-600 hover:text-slate-800 hover:border-slate-300 whitespace-nowrap">
                            Badges
                        </a> --}}
                    </nav>
                </div>
            </div>

            <!-- Participants List -->
            <div class="overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
                <!-- Header -->
                <div class="px-6 py-5 border-b bg-slate-50 border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800">Enrolled Users</h2>
                </div>

                <!-- Filters Section -->
                <div class="p-6 border-b border-slate-200">
                    <form method="GET" action="{{ route('siswa.course.participants', $course->id) }}"
                        id="filterForm">
                        <div class="flex flex-wrap items-end gap-4">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-medium text-slate-700">Match</span>
                                <select name="match"
                                    class="px-3 py-2 text-sm border rounded-lg border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="any" {{ request('match') === 'any' ? 'selected' : '' }}>Any
                                    </option>
                                    <option value="all" {{ request('match') === 'all' ? 'selected' : '' }}>All
                                    </option>
                                </select>
                                <select name="search_field"
                                    class="px-3 py-2 text-sm border rounded-lg border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select</option>
                                    <option value="first_name"
                                        {{ request('search_field') === 'first_name' ? 'selected' : '' }}>First name
                                    </option>
                                    <option value="last_name"
                                        {{ request('search_field') === 'last_name' ? 'selected' : '' }}>Last name
                                    </option>
                                    <option value="email" {{ request('search_field') === 'email' ? 'selected' : '' }}>
                                        Email</option>
                                </select>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search..."
                                    class="px-3 py-2 text-sm border rounded-lg border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @if (request()->hasAny(['first_name', 'last_name', 'search', 'match', 'search_field']))
                                    <a href="{{ route('siswa.course.participants', $course->id) }}"
                                        class="p-2 transition-colors rounded-lg hover:bg-slate-100">
                                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                            <div class="flex gap-3 ml-auto">
                                <a href="{{ route('siswa.course.participants', $course->id) }}"
                                    class="px-4 py-2 text-sm font-medium transition-colors rounded-lg text-slate-700 bg-slate-100 hover:bg-slate-200">
                                    Clear filters
                                </a>
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-semibold text-white transition-colors bg-indigo-600 rounded-lg hover:bg-indigo-700">
                                    Apply filters
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Participants Count -->
                <div class="px-6 py-4 border-b bg-slate-50/50 border-slate-200">
                    <p class="text-sm font-medium text-slate-700">
                        <span class="font-bold">{{ $participants->total() }}</span> participants found
                    </p>
                </div>

                <!-- Alphabet Filter -->
                <div class="px-6 py-4 border-b border-slate-200">
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="w-24 text-sm font-medium text-slate-700">First name</span>
                            <div class="flex flex-wrap gap-1">
                                <a href="{{ route('siswa.course.participants', array_merge(['course' => $course->id], request()->except('first_name', 'page'))) }}"
                                    class="px-2.5 py-1 text-xs font-medium transition-colors rounded {{ !request('first_name') || request('first_name') === 'All' ? 'bg-indigo-600 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                                    All
                                </a>
                                @foreach (range('A', 'Z') as $letter)
                                    <a href="{{ route('siswa.course.participants', array_merge(['course' => $course->id], request()->except('page'), ['first_name' => $letter])) }}"
                                        class="px-2.5 py-1 text-xs font-medium transition-colors rounded {{ request('first_name') === $letter ? 'bg-indigo-600 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                                        {{ $letter }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-24 text-sm font-medium text-slate-700">Last name</span>
                            <div class="flex flex-wrap gap-1">
                                <a href="{{ route('siswa.course.participants', array_merge(['course' => $course->id], request()->except('last_name', 'page'))) }}"
                                    class="px-2.5 py-1 text-xs font-medium transition-colors rounded {{ !request('last_name') || request('last_name') === 'All' ? 'bg-indigo-600 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                                    All
                                </a>
                                @foreach (range('A', 'Z') as $letter)
                                    <a href="{{ route('siswa.course.participants', array_merge(['course' => $course->id], request()->except('page'), ['last_name' => $letter])) }}"
                                        class="px-2.5 py-1 text-xs font-medium transition-colors rounded {{ request('last_name') === $letter ? 'bg-indigo-600 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                                        {{ $letter }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination Top -->
                <div class="flex items-center justify-center px-6 py-3 border-b border-slate-200">
                    {{ $participants->links() }}
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left uppercase text-slate-700">
                                    <a href="{{ route('siswa.course.participants', array_merge(['course' => $course->id], request()->except('sort_by', 'sort_order', 'page'), ['sort_by' => 'name', 'sort_order' => request('sort_by') === 'name' && request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}"
                                        class="flex items-center gap-1 hover:text-indigo-600">
                                        First name / Last name
                                        @if (request('sort_by') === 'name')
                                            @if (request('sort_order') === 'asc')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                                </svg>
                                            @endif
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left uppercase text-slate-700">
                                    Roles
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left uppercase text-slate-700">
                                    Groups
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left uppercase text-slate-700">
                                    Last access to course
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse($participants as $participant)
                                <tr class="transition-colors hover:bg-slate-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex items-center justify-center w-10 h-10 font-semibold text-white rounded-full bg-gradient-to-br from-indigo-500 to-purple-600">
                                                {{ strtoupper(substr($participant->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-medium text-slate-900">
                                                    {{ $participant->user->name }}
                                                </div>
                                                <p class="text-sm text-slate-500">{{ $participant->user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-700 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Student
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500 whitespace-nowrap">
                                        No groups
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-700 whitespace-nowrap">
                                        {{ $participant->last_access ? $participant->last_access->diffForHumans() : 'Never' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-slate-100">
                                                <svg class="w-8 h-8 text-slate-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <p class="text-base font-medium text-slate-900">No participants found</p>
                                            <p class="mt-1 text-sm text-slate-500">There are no enrolled users in this
                                                course yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Bottom -->
                <div class="flex items-center justify-center px-6 py-4 border-t border-slate-200">
                    {{ $participants->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
