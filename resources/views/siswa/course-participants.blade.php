<x-app-layout>
    <div class="min-h-screen py-12 bg-gray-50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('siswa.my-courses') }}"
                           class="inline-flex items-center text-sm font-medium text-gray-500 transition-colors hover:text-teal-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            My Courses
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('siswa.course.show', $course->id) }}" class="ml-1 text-sm font-medium text-gray-500 transition-colors hover:text-teal-600 md:ml-2">{{ $course->nama }}</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-teal-600 md:ml-2">Participants</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="mb-8 overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
                @php
                    $headerGradient = 'from-teal-600 to-cyan-700';
                @endphp

                <div class="relative p-8 bg-gradient-to-br {{ $headerGradient }}">
                    <div class="absolute inset-0 opacity-20">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="header-hexagons" x="0" y="0" width="56" height="100" patternUnits="userSpaceOnUse">
                                    <polygon points="28,0 56,16.66 56,50 28,66.66 0,50 0,16.66" fill="white" fill-opacity="0.3" />
                                    <polygon points="28,66.66 56,83.32 56,116.66 28,133.32 0,116.66 0,83.32" fill="white" fill-opacity="0.3" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#header-hexagons)" />
                        </svg>
                    </div>

                    <div class="absolute top-0 right-0 w-64 h-64 -mt-16 -mr-16 rounded-full bg-white/10 blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 -mb-10 -ml-10 rounded-full bg-teal-400/20 blur-2xl"></div>

                    <div class="relative">
                        <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
                            <div class="flex-1">
                                <div class="inline-flex items-center px-3 py-1 mb-3 text-xs font-bold tracking-wider text-white uppercase bg-white border rounded-full bg-opacity-20 backdrop-blur-md border-white/20">
                                    {{ $course->mapel->nama ?? 'Mata Pelajaran' }}
                                </div>
                                <h1 class="mb-2 text-3xl font-bold tracking-tight text-white">{{ $course->nama }}</h1>
                            </div>
                            <div class="flex items-center gap-4 p-4 border shadow-lg border-white/20 rounded-xl bg-white/10 backdrop-blur-md">
                                <div class="text-center min-w-[80px]">
                                    <p class="text-3xl font-bold text-white">{{ $participants->total() }}</p>
                                    <p class="text-xs font-medium tracking-wide uppercase text-cyan-100">Participants</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white border-b border-gray-200">
                    <nav class="flex px-6 -mb-px space-x-8 overflow-x-auto" aria-label="Tabs">
                        <a href="{{ route('siswa.course.show', $course->id) }}"
                           class="px-1 py-4 text-sm font-medium text-gray-500 transition-colors border-b-2 border-transparent hover:text-gray-800 hover:border-gray-300 whitespace-nowrap">
                            Course Content
                        </a>
                        <a href="#"
                           class="px-1 py-4 text-sm font-bold text-teal-600 transition-colors border-b-2 border-teal-600 whitespace-nowrap">
                            Participants
                        </a>
                        <a href="{{ route('siswa.course.grades', $course->id) }}"
                           class="px-1 py-4 text-sm font-medium text-gray-500 transition-colors border-b-2 border-transparent hover:text-gray-800 hover:border-gray-300 whitespace-nowrap">
                            Grades
                        </a>
                        <a href="{{ route('siswa.course.competencies', $course->id) }}"
                           class="px-1 py-4 text-sm font-medium text-gray-500 transition-colors border-b-2 border-transparent hover:text-gray-800 hover:border-gray-300 whitespace-nowrap">
                            Competencies
                        </a>
                    </nav>
                </div>
            </div>

            <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-xl font-bold text-gray-800">Enrolled Users</h2>
                </div>

                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('siswa.course.participants', $course->id) }}" id="filterForm">
                        <div class="flex flex-col gap-4">
                            <div class="flex flex-wrap items-end gap-4">
                                <div class="flex flex-wrap items-center gap-3">
                                    <div class="flex flex-col gap-1">
                                        <label class="text-xs font-semibold text-gray-500 uppercase">Match</label>
                                        <select name="match" class="px-3 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-gray-50">
                                            <option value="any" {{ request('match') === 'any' ? 'selected' : '' }}>Any</option>
                                            <option value="all" {{ request('match') === 'all' ? 'selected' : '' }}>All</option>
                                        </select>
                                    </div>
                                    
                                    <div class="flex flex-col gap-1">
                                        <label class="text-xs font-semibold text-gray-500 uppercase">Field</label>
                                        <select name="search_field" class="px-3 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-gray-50">
                                            <option value="">Select Field</option>
                                            <option value="first_name" {{ request('search_field') === 'first_name' ? 'selected' : '' }}>First name</option>
                                            <option value="last_name" {{ request('search_field') === 'last_name' ? 'selected' : '' }}>Last name</option>
                                            <option value="email" {{ request('search_field') === 'email' ? 'selected' : '' }}>Email</option>
                                        </select>
                                    </div>

                                    <div class="flex flex-col flex-grow gap-1 sm:flex-grow-0">
                                        <label class="text-xs font-semibold text-gray-500 uppercase">Keyword</label>
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            placeholder="Search..."
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg sm:w-48 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                    </div>
                                    
                                    @if (request()->hasAny(['first_name', 'last_name', 'search', 'match', 'search_field']))
                                        <a href="{{ route('siswa.course.participants', $course->id) }}"
                                           class="p-2 mt-5 text-gray-400 transition-colors rounded-lg hover:bg-red-50 hover:text-red-500" title="Clear All Filters">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>

                                <div class="flex gap-3 mt-2 ml-auto sm:mt-0">
                                    <button type="submit"
                                        class="px-5 py-2.5 text-sm font-bold text-white transition-all bg-teal-600 rounded-lg hover:bg-teal-700 shadow-sm hover:shadow-md">
                                        Apply Filters
                                    </button>
                                </div>
                            </div>

                            <div class="pt-4 space-y-3 border-t border-gray-100">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                    <span class="w-24 text-xs font-bold tracking-wide text-gray-500 uppercase">First name</span>
                                    <div class="flex flex-wrap gap-1">
                                        <a href="{{ route('siswa.course.participants', array_merge(['course' => $course->id], request()->except('first_name', 'page'))) }}"
                                           class="px-2 py-1 text-xs font-medium transition-colors rounded {{ !request('first_name') || request('first_name') === 'All' ? 'bg-teal-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                                            All
                                        </a>
                                        @foreach (range('A', 'Z') as $letter)
                                            <a href="{{ route('siswa.course.participants', array_merge(['course' => $course->id], request()->except('page'), ['first_name' => $letter])) }}"
                                               class="px-2 py-1 text-xs font-medium transition-colors rounded {{ request('first_name') === $letter ? 'bg-teal-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                                                {{ $letter }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                    <span class="w-24 text-xs font-bold tracking-wide text-gray-500 uppercase">Last name</span>
                                    <div class="flex flex-wrap gap-1">
                                        <a href="{{ route('siswa.course.participants', array_merge(['course' => $course->id], request()->except('last_name', 'page'))) }}"
                                           class="px-2 py-1 text-xs font-medium transition-colors rounded {{ !request('last_name') || request('last_name') === 'All' ? 'bg-teal-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                                            All
                                        </a>
                                        @foreach (range('A', 'Z') as $letter)
                                            <a href="{{ route('siswa.course.participants', array_merge(['course' => $course->id], request()->except('page'), ['last_name' => $letter])) }}"
                                               class="px-2 py-1 text-xs font-medium transition-colors rounded {{ request('last_name') === $letter ? 'bg-teal-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                                                {{ $letter }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="px-6 py-3 border-b border-gray-200 bg-teal-50/50">
                    <p class="text-sm font-medium text-teal-800">
                        <span class="font-bold">{{ $participants->total() }}</span> participants found
                    </p>
                </div>

                <div class="px-6 py-3 bg-white border-b border-gray-200">
                    {{ $participants->links() }}
                </div>

                <div class="overflow-x-auto bg-white">
                    <table class="w-full">
                        <thead class="border-b border-gray-200 bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">
                                    <a href="{{ route('siswa.course.participants', array_merge(['course' => $course->id], request()->except('sort_by', 'sort_order', 'page'), ['sort_by' => 'name', 'sort_order' => request('sort_by') === 'name' && request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}"
                                       class="flex items-center gap-1 transition-colors hover:text-teal-600 group">
                                        First name / Last name
                                        @if (request('sort_by') === 'name')
                                            @if (request('sort_order') === 'asc')
                                                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                                            @else
                                                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                                            @endif
                                        @else
                                            <svg class="w-4 h-4 text-gray-400 transition-opacity opacity-0 group-hover:text-teal-500 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-4 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Roles</th>
                                <th class="px-6 py-4 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Groups</th>
                                <th class="px-6 py-4 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Last access to course</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($participants as $participant)
                                <tr class="transition-colors hover:bg-gray-50 group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-4">
                                            <div class="flex items-center justify-center w-10 h-10 font-bold text-white rounded-full shadow-sm bg-gradient-to-br from-teal-400 to-blue-500">
                                                {{ strtoupper(substr($participant->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-800 transition-colors group-hover:text-teal-700">
                                                    {{ $participant->user->name }}
                                                </div>
                                                <p class="text-sm text-gray-500">{{ $participant->user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-cyan-100 text-cyan-800 border border-cyan-200">
                                            Student
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm italic text-gray-400 whitespace-nowrap">
                                        No groups
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                        {{ $participant->last_access ? $participant->last_access->diffForHumans() : 'Never' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="flex items-center justify-center w-16 h-16 mb-4 border border-gray-300 border-dashed rounded-full bg-gray-50">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                </svg>
                                            </div>
                                            <p class="text-base font-bold text-gray-900">No participants found</p>
                                            <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or check back later.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-center px-6 py-5 bg-white border-t border-gray-200">
                    {{ $participants->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>