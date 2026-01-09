@php
use Illuminate\Support\Str;
use Carbon\Carbon;
@endphp

<div class="space-y-3">
    @forelse ($records as $record)
        @php
            $course = $record;
            $kelasName = $course->kelas?->nama ?? '-';
            $mapelName = $course->mapel?->nama ?? '-';
            $siswaCount = $course->kelas?->siswas()->count() ?? 0;
            $modulCount = $course->moduls()->count();
            $publishedCount = $course->moduls()->whereNotNull('publish_at')->where('publish_at', '<=', now())->count();
            $progress = $modulCount > 0 ? round(($publishedCount / $modulCount) * 100) : 0;

            $colorClasses = [
                'bg-blue-500',
                'bg-purple-500',
                'bg-green-500',
                'bg-yellow-500',
                'bg-orange-500',
                'bg-rose-500',
                'bg-teal-500',
                'bg-indigo-500',
            ];
            $colorIndex = $course->mapel?->id ?? 0;
            $color = $colorClasses[$colorIndex % count($colorClasses)];
        @endphp

        <div class="group relative overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition-all hover:shadow-md dark:border-gray-700 dark:bg-gray-900">
            <div class="flex items-center justify-between gap-6 p-4">
                <!-- Course Info -->
                <div class="flex min-w-0 flex-1 items-center gap-4">
                    <!-- Icon with Color -->
                    <div class="flex-shrink-0">
                        <div class="flex h-14 w-14 items-center justify-center rounded-xl {{ $color }} shadow-sm">
                            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    </div>

                    <!-- Text Info -->
                    <div class="min-w-0 flex-1">
                        <h3 class="truncate text-base font-semibold text-gray-900 dark:text-white">
                            {{ $course->nama }}
                        </h3>
                        <p class="mt-0.5 truncate text-sm text-gray-600 dark:text-gray-400">
                            {{ $kelasName }} • {{ $mapelName }}
                        </p>
                    </div>
                </div>

                <!-- Stats Section -->
                <div class="flex items-center gap-8">
                    <!-- Siswa -->
                    <div class="text-center">
                        <div class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ $siswaCount }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            Siswa
                        </div>
                    </div>

                    <!-- Bab -->
                    <div class="text-center">
                        <div class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ $modulCount }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            Bab
                        </div>
                    </div>

                    <!-- Progress -->
                    <div class="text-center">
                        <div class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ $progress }}%
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            Progress
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="ml-2">
                        @php
                            $editUrl = \App\Filament\Resources\CourseResource::getUrl('edit', ['record' => $course]);
                        @endphp
                        <a href="{{ $editUrl }}"
                           class="inline-flex items-center gap-1.5 rounded-lg px-4 py-2 text-sm font-semibold text-green-600 transition-colors hover:bg-green-50 dark:text-green-400 dark:hover:bg-green-950/50">
                            Kelola
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 p-12 text-center dark:border-gray-700 dark:bg-gray-900">
            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <p class="mt-4 text-sm font-medium text-gray-900 dark:text-white">Tidak ada course yang ditemukan</p>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan membuat course baru.</p>
        </div>
    @endforelse
</div>
