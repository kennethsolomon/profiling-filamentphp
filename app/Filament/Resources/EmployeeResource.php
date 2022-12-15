<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\City;
use App\Models\Country;
use App\Models\Employee;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
  protected static ?string $model = Employee::class;

  protected static ?string $navigationIcon = 'heroicon-o-briefcase';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Card::make()
          ->schema([

            Select::make('country_id')
              ->label('Country')
              ->options(Country::all()->pluck('name', 'id')->toArray())->reactive()
              ->afterStateUpdated(fn (callable $set) => $set('state_id', null)),

            Select::make('state_id')
              ->label('State')
              ->options(function (callable $get) {
                $country = Country::find($get('country_id'));
                return $country ? $country->states->pluck('name', 'id') : State::all()->pluck('name', 'id');
              })->reactive()
              ->afterStateUpdated(fn (callable $set) => $set('city_id', null)),

            Select::make('city_id')
              ->label('City')
              ->options(function (callable $get) {
                $state = State::find($get('state_id'));
                return $state ? $state->cities->pluck('name', 'id') : City::all()->pluck('name', 'id');
              })->reactive(),

            Select::make('department_id')->relationship('department', 'name')->searchable()->preload()->required(),
            Forms\Components\TextInput::make('first_name')
              ->required()
              ->maxLength(255),
            Forms\Components\TextInput::make('last_name')
              ->required()
              ->maxLength(255),
            Forms\Components\TextInput::make('address')
              ->required()
              ->maxLength(255),
            Forms\Components\TextInput::make('zip_code')
              ->required()
              ->maxLength(255),
            Forms\Components\DatePicker::make('birth_date')
              ->required(),
            Forms\Components\DatePicker::make('date_hired')
              ->required(),
          ])
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('first_name')->searchable(),
        Tables\Columns\TextColumn::make('last_name')->searchable(),
        Tables\Columns\TextColumn::make('department.name')->searchable()->sortable(),
        Tables\Columns\TextColumn::make('address')->searchable(),
        Tables\Columns\TextColumn::make('zip_code')->searchable(),
        Tables\Columns\TextColumn::make('birth_date')
          ->date(),
        Tables\Columns\TextColumn::make('date_hired')
          ->date()->sortable(),
        Tables\Columns\TextColumn::make('created_at')
          ->dateTime(),
        Tables\Columns\TextColumn::make('updated_at')
          ->dateTime(),
      ])
      ->filters([
        SelectFilter::make('department')->relationship('department', 'name'),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\DeleteBulkAction::make(),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      //
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListEmployees::route('/'),
      'create' => Pages\CreateEmployee::route('/create'),
      'edit' => Pages\EditEmployee::route('/{record}/edit'),
    ];
  }
}
