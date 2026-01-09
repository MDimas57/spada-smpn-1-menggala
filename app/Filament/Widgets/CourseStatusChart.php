<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use Filament\Widgets\ChartWidget;

class CourseStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Status Course';
    protected static ?int $sort = 4;

    public static function canView(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    protected function getData(): array
    {
        $draft = Course::where('status', 'draft')->count();
        $published = Course::where('status', 'published')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Course',
                    'data' => [$draft, $published],
                    'backgroundColor' => [
                        'rgba(156, 163, 175, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                    ],
                ],
            ],
            'labels' => ['Draft', 'Published'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
