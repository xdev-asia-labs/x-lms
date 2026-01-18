<?php

namespace App\Filament\Widgets;

use App\Models\CourseEnrollment;
use App\Models\LessonProgress;
use App\Models\Member;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected function getStats(): array
    {
        // Total Members
        $totalMembers = Member::count();
        $newMembersThisMonth = Member::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Total Courses
        $totalCourses = Post::where('post_type', 'course')
            ->where('status', 'published')
            ->count();
        $coursesThisMonth = Post::where('post_type', 'course')
            ->where('status', 'published')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Total Enrollments
        $totalEnrollments = CourseEnrollment::count();
        $enrollmentsThisMonth = CourseEnrollment::whereMonth('enrolled_at', now()->month)
            ->whereYear('enrolled_at', now()->year)
            ->count();

        // Completed Lessons
        $completedLessons = LessonProgress::where('completed', true)->count();
        $completedThisMonth = LessonProgress::where('completed', true)
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();

        return [
            Stat::make('Total Members', $totalMembers)
                ->description($newMembersThisMonth.' new this month')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 12, 15, 18, 25, 30, $newMembersThisMonth]),

            Stat::make('Total Courses', $totalCourses)
                ->description($coursesThisMonth.' published this month')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary')
                ->chart([5, 10, 12, 15, 18, 20, $coursesThisMonth]),

            Stat::make('Total Enrollments', $totalEnrollments)
                ->description($enrollmentsThisMonth.' new this month')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning')
                ->chart([20, 35, 45, 60, 75, 85, $enrollmentsThisMonth]),

            Stat::make('Completed Lessons', $completedLessons)
                ->description($completedThisMonth.' completed this month')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([50, 80, 120, 150, 180, 200, $completedThisMonth]),
        ];
    }
}
