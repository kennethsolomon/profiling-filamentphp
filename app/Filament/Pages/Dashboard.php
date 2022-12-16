<?php

namespace App\Filament\Pages;

use App\Filament\Resources\EmployeeResource\Widgets\EmployeeOverview;
use App\Filament\Resources\EmployeeResource\Widgets\EmployeesOverview;
use Filament\Facades\Filament;
use Filament\Forms\Components\Card;
use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{

	protected static ?string $navigationIcon = 'heroicon-o-home';

	protected function getWidgets(): array
	{
		return [
			EmployeesOverview::class
		];
	}
}
