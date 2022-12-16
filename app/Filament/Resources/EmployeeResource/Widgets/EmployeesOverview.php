<?php

namespace App\Filament\Resources\EmployeeResource\Widgets;

use App\Models\Employee;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class EmployeesOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $fromDate_this_month = Carbon::now()->startOfMonth()->toDateString();
        $tillDate_this_month = Carbon::now()->endOfMonth()->toDateString();
        $total_employee_this_month = Employee::whereBetween('created_at', [$fromDate_this_month, $tillDate_this_month])->count();

        $fromDate_last_month = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $tillDate_last_month = Carbon::now()->subMonth()->endOfMonth()->toDateString();
        $total_employee_last_month = Employee::whereBetween('created_at', [$fromDate_last_month, $tillDate_last_month])->count();

        $total_percentage_increase = ($total_employee_last_month - $total_employee_this_month) != 0
            ? round(($total_employee_this_month - $total_employee_last_month)  / $total_employee_this_month * 100, 2) : 0;
        $increase = $total_employee_last_month - $total_employee_this_month;

        return [
            Card::make('Total Employees', Employee::count())
                ->description($total_percentage_increase . '% (Monthly Percentage)')
                ->descriptionIcon($total_percentage_increase > 0 ? 'heroicon-s-trending-up' : 'heroicon-s-trending-down')
                ->color($total_percentage_increase > 0 ? 'success' : 'danger'),
            Card::make('Total Active Employees', Employee::whereIsActive(true)->count())->description('Working Employee')->descriptionIcon('heroicon-o-briefcase'),
            Card::make('This Month Total Employee', $total_employee_this_month)->description('Newly Hired Employee Counter')->descriptionIcon('heroicon-o-academic-cap'),
            Card::make('Last Month Total Employee', $total_employee_last_month)->description('Last Month Hired Employee Counter')->descriptionIcon('heroicon-o-briefcase'),
        ];
    }
}
