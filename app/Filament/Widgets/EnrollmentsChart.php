<?php

namespace App\Filament\Widgets;

use App\Models\CourseEnrollment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class EnrollmentsChart extends ChartWidget
{
    protected static ?int $sort = 2;

    public ?string $filter = '6months';

    public function getHeading(): ?string
    {
        return 'Monthly Enrollments';
    }

    protected function getData(): array
    {
        $months = match ($this->filter) {
            '3months' => 3,
            '6months' => 6,
            '12months' => 12,
            default => 6,
        };

        $data = CourseEnrollment::select(
            DB::raw('DATE_FORMAT(enrolled_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
            ->where('enrolled_at', '>=', now()->subMonths($months))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = [];
        $values = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $monthLabel = $date->format('M Y');

            $labels[] = $monthLabel;
            $values[] = $data->firstWhere('month', $monthKey)?->count ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Course Enrollments',
                    'data' => $values,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            '3months' => 'Last 3 months',
            '6months' => 'Last 6 months',
            '12months' => 'Last 12 months',
        ];
    }
}
