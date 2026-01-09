<x-app-layout>
    <div class="min-h-screen py-12 bg-slate-50/50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <div class="flex flex-col justify-between gap-4 mb-8 md:flex-row md:items-center">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-800">My Courses</h2>
                    <p class="mt-1 text-slate-500">
                        Pilih mata pelajaran untuk kelas <span class="font-semibold text-indigo-600">{{ Auth::user()->siswa->kelas->nama ?? '-' }}</span>. Semangat belajar! 🚀
                    </p>
                </div>
                <div class="hidden md:block">
                     <span class="inline-flex items-center px-3 py-1 text-xs font-medium bg-white border rounded-full shadow-sm border-slate-200 text-slate-500">
                        Total: {{ isset($courses) ? $courses->count() : 0 }} Mata Pelajaran
                    </span>
                </div>
            </div>

            <div class="p-6 bg-white border shadow-sm border-slate-200 rounded-3xl">

                <div class="mb-6">
                    <h3 class="mb-4 text-xl font-bold text-slate-800">Course overview</h3>
                    <hr class="mb-6 border-slate-200">

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:flex-wrap">

                        <select id="filter-select" onchange="filterCourses()"
                                class="px-4 py-2 pr-10 text-sm border-slate-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 text-slate-600 min-w-[120px] cursor-pointer">
                            <option value="all">All</option>
                            <option value="starred">Starred</option>
                        </select>

                        <div class="relative flex-grow sm:flex-grow-0">
                            <input type="text" id="search-input" onkeyup="filterCourses()" placeholder="Search"
                                   class="w-full px-4 py-2 text-sm rounded-md sm:w-64 border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-slate-600">
                        </div>

                        <select id="sort-select" onchange="sortCourses()"
                                class="px-4 py-2 pr-10 text-sm border-slate-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 text-slate-600 min-w-[200px] cursor-pointer">
                            <option value="name">Sort by course name</option>
                            <option value="latest" selected>Sort by last accessed</option>
                        </select>

                        <select id="view-select" onchange="toggleView()"
                                class="px-4 py-2 pr-10 text-sm border-slate-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 text-slate-600 min-w-[100px] cursor-pointer">
                            <option value="card">Card</option>
                            <option value="list">List</option>
                        </select>

                    </div>
                </div>

                <div id="courses-container" class="grid grid-cols-1 gap-6 transition-all duration-300 md:grid-cols-2 lg:grid-cols-3">
                    @if(isset($courses) && $courses->count() > 0)
                        @foreach($courses as $index => $course)
                            @php
                                // Array warna gradient
                                $gradients = [
                                    'from-amber-400 to-orange-500',
                                    'from-blue-400 to-indigo-600',
                                    'from-slate-400 to-slate-600',
                                    'from-emerald-400 to-teal-600',
                                    'from-purple-400 to-pink-600',
                                    'from-rose-400 to-red-600',
                                ];
                                $gradient = $gradients[$index % count($gradients)];

                                // Logic Cek Starred (Dari Controller)
                                $isStarred = in_array($course->id, $starredCourseIds ?? []);
                            @endphp

                            <div class="relative flex flex-col overflow-hidden transition-all duration-300 bg-white border shadow-sm course-card group rounded-2xl border-slate-200 hover:shadow-xl hover:-translate-y-1"
                                 data-starred="{{ $isStarred ? 'true' : 'false' }}"
                                 data-name="{{ strtolower($course->nama) }}"
                                 data-time="{{ $course->created_at->timestamp }}"
                                 data-id="{{ $course->id }}">

                                <div class="course-banner relative h-48 w-full overflow-hidden bg-gradient-to-br {{ $gradient }} transition-all duration-300">

                                    <div class="absolute inset-0 opacity-30">
                                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                                            <defs>
                                                <pattern id="hexagons-{{ $index }}" x="0" y="0" width="56" height="100" patternUnits="userSpaceOnUse">
                                                    <polygon points="28,0 56,16.66 56,50 28,66.66 0,50 0,16.66" fill="white" fill-opacity="0.1"/>
                                                    <polygon points="28,66.66 56,83.32 56,116.66 28,133.32 0,116.66 0,83.32" fill="white" fill-opacity="0.1"/>
                                                </pattern>
                                            </defs>
                                            <rect width="100%" height="100%" fill="url(#hexagons-{{ $index }})"/>
                                        </svg>
                                    </div>

                                    <div class="absolute top-4 left-4">
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-bold tracking-wider text-white uppercase bg-white rounded-lg shadow-lg bg-opacity-30 backdrop-blur-sm">
                                            {{ $course->mapel->nama ?? 'Umum' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex flex-col w-full p-5 transition-all duration-300 course-content">

                                    <div class="flex items-start justify-between gap-3 mb-3">
                                        <h3 class="text-base font-bold leading-tight transition-colors text-slate-800 group-hover:text-indigo-600 line-clamp-2">
                                            <a href="{{ route('siswa.course.show', $course->id) }}">
                                                {{ $course->nama }}
                                            </a>
                                        </h3>

                                        <button onclick="toggleStar(this, {{ $course->id }})" class="p-1 -mt-1 -mr-1 transition-transform rounded-full shrink-0 focus:outline-none hover:scale-110 hover:bg-slate-50" title="Star this course">
                                            <svg class="w-6 h-6 star-icon {{ $isStarred ? 'text-amber-400' : 'text-slate-300' }}"
                                                 fill="{{ $isStarred ? 'currentColor' : 'none' }}"
                                                 stroke="currentColor"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="items-center hidden gap-4 pt-3 mb-3 border-t course-details-list border-slate-100">
                                        <div class="flex items-center gap-1.5 text-slate-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                            <span class="text-sm font-medium">{{ $course->moduls_count ?? 0 }} Modul</span>
                                        </div>
                                        <div class="flex items-center gap-1 text-xs font-medium text-slate-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $course->created_at->diffForHumans() }}
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="flex items-center justify-center w-6 h-6 text-xs font-bold text-white rounded-full bg-gradient-to-br {{ $gradient }}">
                                                {{ substr($course->guru->user->name ?? 'G', 0, 1) }}
                                            </div>
                                            <span class="text-xs text-slate-600">{{ $course->guru->user->name ?? 'Guru' }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pt-3 mb-3 border-t course-details-card border-slate-100">
                                        <div class="flex items-center gap-1.5 text-slate-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                            <span class="text-sm font-medium">{{ $course->moduls_count ?? 0 }} Modul</span>
                                        </div>
                                        <span class="flex items-center gap-1 text-xs font-medium text-slate-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $course->created_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between mt-auto course-footer-card">
                                        <div class="flex items-center gap-2">
                                            <div class="flex items-center justify-center w-9 h-9 text-xs font-bold text-white rounded-full bg-gradient-to-br {{ $gradient }}">
                                                {{ substr($course->guru->user->name ?? 'G', 0, 1) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-xs font-semibold text-slate-700">{{ $course->guru->user->name ?? 'Guru' }}</span>
                                                <span class="text-[10px] text-slate-400">Pengajar</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('siswa.course.show', $course->id) }}" class="inline-flex items-center justify-center text-indigo-600 transition-all duration-200 rounded-full w-9 h-9 bg-indigo-50 hover:bg-indigo-600 hover:text-white group-hover:scale-110">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </div>

                                    <div class="items-center justify-between hidden mt-auto course-footer-list">
                                        <a href="{{ route('siswa.course.show', $course->id) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-indigo-600 transition-all duration-200 rounded-lg bg-indigo-50 hover:bg-indigo-600 hover:text-white">
                                            <span>Buka Course</span>
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-1 md:col-span-2 lg:col-span-3">
                            <div class="flex flex-col items-center justify-center px-4 py-16 text-center border border-dashed bg-slate-50 rounded-2xl border-slate-300">
                                <div class="flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-indigo-50">
                                    <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-slate-900">Belum ada mata pelajaran tersedia</h3>
                                <p class="max-w-sm mt-1 text-sm text-slate-500">Sepertinya guru belum membuat course untuk kelas Anda.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* CSS untuk mode list yang berbeda dari card */
        .list-view {
            grid-template-columns: 1fr !important;
            gap: 1rem !important;
        }

        .list-view .course-card {
            flex-direction: row !important;
            height: auto !important;
            min-height: 160px;
        }

        .list-view .course-banner {
            width: 140px !important;
            height: 100% !important;
            min-height: 160px;
            border-radius: 12px 0 0 12px !important;
        }

        .list-view .course-content {
            flex: 1;
            padding: 1.25rem !important;
        }

        .list-view .course-details-list {
            display: flex !important;
        }

        .list-view .course-details-card {
            display: none !important;
        }

        .list-view .course-footer-list {
            display: flex !important;
        }

        .list-view .course-footer-card {
            display: none !important;
        }

        .list-view .course-content h3 {
            font-size: 1rem !important;
            margin-bottom: 0.75rem !important;
        }

        /* Responsif untuk mobile */
        @media (max-width: 768px) {
            .list-view .course-card {
                flex-direction: column !important;
                min-height: auto;
            }

            .list-view .course-banner {
                width: 100% !important;
                height: 140px !important;
                min-height: 140px;
                border-radius: 12px 12px 0 0 !important;
            }

            .list-view .course-details-list {
                flex-wrap: wrap;
                gap: 0.75rem !important;
            }

            .list-view .course-content {
                padding: 1rem !important;
            }
        }

        /* Untuk tablet */
        @media (min-width: 769px) and (max-width: 1024px) {
            .list-view .course-banner {
                width: 120px !important;
            }
        }
    </style>

    <script>
        const toggleRouteBase = "/siswa/course";
        const csrfToken = "{{ csrf_token() }}";

        // === INITIALIZE ===
        document.addEventListener('DOMContentLoaded', () => {
            sortCourses(); // Lakukan sorting default saat halaman dimuat
        });

        // === 1. LOGIC VIEW TOGGLE (CARD/LIST) - VERSI YANG LEBIH BAIK ===
        function toggleView() {
            const viewMode = document.getElementById('view-select').value;
            const container = document.getElementById('courses-container');
            const cards = document.querySelectorAll('.course-card');

            if (viewMode === 'list') {
                // Mode List
                container.classList.remove('grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3');
                container.classList.add('list-view');

                cards.forEach(card => {
                    card.classList.add('list-view');
                    // Tampilkan elemen list, sembunyikan elemen card
                    const detailsList = card.querySelector('.course-details-list');
                    const detailsCard = card.querySelector('.course-details-card');
                    const footerList = card.querySelector('.course-footer-list');
                    const footerCard = card.querySelector('.course-footer-card');

                    if (detailsList) detailsList.classList.remove('hidden');
                    if (detailsCard) detailsCard.classList.add('hidden');
                    if (footerList) footerList.classList.remove('hidden');
                    if (footerCard) footerCard.classList.add('hidden');
                });

            } else {
                // Mode Card (Grid)
                container.classList.remove('list-view');
                container.classList.add('grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3');

                cards.forEach(card => {
                    card.classList.remove('list-view');
                    // Tampilkan elemen card, sembunyikan elemen list
                    const detailsList = card.querySelector('.course-details-list');
                    const detailsCard = card.querySelector('.course-details-card');
                    const footerList = card.querySelector('.course-footer-list');
                    const footerCard = card.querySelector('.course-footer-card');

                    if (detailsList) detailsList.classList.add('hidden');
                    if (detailsCard) detailsCard.classList.remove('hidden');
                    if (footerList) footerList.classList.add('hidden');
                    if (footerCard) footerCard.classList.remove('hidden');
                });
            }
        }

        // === 2. LOGIC SORTING ===
        function sortCourses() {
            const container = document.getElementById('courses-container');
            const cards = Array.from(container.querySelectorAll('.course-card'));
            const sortValue = document.getElementById('sort-select').value;

            cards.sort((a, b) => {
                const nameA = a.getAttribute('data-name');
                const nameB = b.getAttribute('data-name');
                const timeA = parseInt(a.getAttribute('data-time'));
                const timeB = parseInt(b.getAttribute('data-time'));

                if (sortValue === 'name') {
                    return nameA.localeCompare(nameB);
                } else if (sortValue === 'latest') {
                    // Descending (Terbaru di atas)
                    return timeB - timeA;
                }
            });

            // Re-append elements (DOM manipulation)
            cards.forEach(card => container.appendChild(card));
        }

        // === 3. LOGIC FILTERING (SEARCH & STARRED) ===
        function filterCourses() {
            const filterValue = document.getElementById('filter-select').value;
            const searchValue = document.getElementById('search-input').value.toLowerCase();
            const cards = document.querySelectorAll('.course-card');

            cards.forEach(card => {
                const isStarred = card.getAttribute('data-starred') === 'true';
                const courseName = card.getAttribute('data-name');

                // Cek kondisi Starred
                let matchesFilter = (filterValue === 'starred') ? isStarred : true;

                // Cek kondisi Search
                let matchesSearch = courseName.includes(searchValue);

                // Tampilkan hanya jika memenuhi KEDUA kondisi
                if (matchesFilter && matchesSearch) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // === 4. LOGIC TOGGLE STAR (AJAX) ===
        async function toggleStar(button, courseId) {
            const card = button.closest('.course-card');
            const svg = button.querySelector('svg');
            const isStarredCurrently = card.getAttribute('data-starred') === 'true';

            // Optimistic UI Update (Ubah tampilan dulu biar cepat)
            if (isStarredCurrently) {
                // Unstar action
                card.setAttribute('data-starred', 'false');
                svg.setAttribute('fill', 'none');
                svg.classList.remove('text-amber-400');
                svg.classList.add('text-slate-300');
            } else {
                // Star action
                card.setAttribute('data-starred', 'true');
                svg.setAttribute('fill', 'currentColor');
                svg.classList.remove('text-slate-300');
                svg.classList.add('text-amber-400');
            }

            // Refresh filter agar jika kita di tab "Starred" dan unstar, itemnya hilang
            filterCourses();

            // Send request to backend
            try {
                const response = await fetch(`${toggleRouteBase}/${courseId}/toggle-star`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if(!response.ok) throw new Error("Gagal update");

            } catch (error) {
                console.error(error);
                alert("Gagal menyimpan perubahan. Cek koneksi internet Anda.");
                // Opsional: Revert UI change here if needed
            }
        }

        // === 5. HANDLE RESIZE EVENT ===
        window.addEventListener('resize', function() {
            const viewMode = document.getElementById('view-select').value;
            if (viewMode === 'list') {
                // Refresh layout list saat resize
                toggleView();
            }
        });
    </script>
</x-app-layout>
