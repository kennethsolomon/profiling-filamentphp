<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Filament\Resources\EmployeeResource\RelationManagers\AttachmentsRelationManager;
use App\Models\City;
use App\Models\Country;
use App\Models\Employee;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
  protected static ?string $model = Employee::class;

  protected static ?string $navigationIcon = 'heroicon-o-briefcase';
  protected static ?string $navigationGroup = 'Employee Profile';
  protected static ?int $navigationSort = 1;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Card::make()
          ->schema([
            FileUpload::make('photo')->preserveFilenames()->enableDownload()->columnSpan('full'),

            Select::make('department_id')->relationship('department', 'name')->searchable()->preload()->required(),
            TextInput::make('employee_number')->required()->maxLength(255),
            TextInput::make('first_name')->required()->maxLength(255),
            TextInput::make('middle_name')->maxLength(255),
            TextInput::make('last_name')->required()->maxLength(255),
            TextInput::make('address')->required()->maxLength(255),
            DatePicker::make('birth_date')->required(),
            TextInput::make('phone_number')->tel()->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')->required(),
            TextInput::make('zip_code')->required()->maxLength(255),
            DatePicker::make('date_hired')->required(),
            Toggle::make('is_active')->required(),
          ])
          ->columns(3)
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        ImageColumn::make('photo'),
        TextColumn::make('department.name')->searchable()->sortable(),
        TextColumn::make('employee_number')->searchable(),
        TextColumn::make('first_name')->searchable(),
        TextColumn::make('middle_name')->searchable(),
        TextColumn::make('last_name')->searchable(),
        TextColumn::make('address')->searchable(),
        TextColumn::make('birth_date')->date(),
        TextColumn::make('phone_number')->searchable(),
        TextColumn::make('zip_code')->searchable(),
        TextColumn::make('date_hired')->date()->sortable(),
        IconColumn::make('is_active')->boolean()->searchable()->sortable(),
        TextColumn::make('created_at')->dateTime(),
        TextColumn::make('updated_at')->dateTime(),
      ])
      ->filters([
        SelectFilter::make('department')->relationship('department', 'name'),
        Filter::make('is_active')
          ->query(fn (Builder $query): Builder => $query->where('is_active', true))
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
      AttachmentsRelationManager::class
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
