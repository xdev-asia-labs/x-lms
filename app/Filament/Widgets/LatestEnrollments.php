<?php

namespace App\Filament\Widgets;

use App\Models\CourseEnrollment;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestEnrollments extends TableWidget
{
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                CourseEnrollment::query()
                    ->with(['member', 'course'])
                    ->latest('enrolled_at')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('member.name')
                    ->label('Member')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('member.email')
                    ->label('Email')
                    ->searchable(),
                    
                TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->limit(40),
                    
                TextColumn::make('enrolled_at')
                    ->label('Enrolled At')
                    ->dateTime()
                    ->sortable(),
                    
                TextColumn::make('completed')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Completed' : 'In Progress')
                    ->color(fn (bool $state): string => $state ? 'success' : 'warning'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn (CourseEnrollment $record): string => 
                        route('admin.filament.resources.members.view', ['record' => $record->member_id])
                    ),
            ]);
    }
}
