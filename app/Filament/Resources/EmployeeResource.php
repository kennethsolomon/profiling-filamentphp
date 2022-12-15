<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
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

  protected static ?string $navigationIcon = 'heroicon-o-collection';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Card::make()
          ->schema([
            Select::make('country_id')->relationship('country', 'name')->searchable()->preload()->required(),
            Select::make('state_id')->relationship('state', 'name')->searchable()->preload()->required(),
            Select::make('city_id')->relationship('city', 'name')->searchable()->preload()->required(),
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
