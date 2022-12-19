<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Employee;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Resources\Pages\Page;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Card;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
  protected static ?string $model = User::class;

  protected static ?string $navigationIcon = 'heroicon-o-user';
  protected static ?string $navigationGroup = 'User Management';
  protected static ?int $navigationSort = 3;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Card::make([
          Forms\Components\TextInput::make('name')
            ->required()
            ->maxLength(255),
          Forms\Components\TextInput::make('email')
            ->label('Email Address')
            ->email()
            ->required()
            ->maxLength(255),
          Forms\Components\TextInput::make('password')
            ->password()
            ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
            ->minLength(8)
            ->same('passwordConfirmation')
            ->dehydrated(fn ($state) => filled($state))
            ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
          Forms\Components\TextInput::make('passwordConfirmation')
            ->password()
            ->label('Password Confirmation')
            ->minLength(8)
            ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
            ->dehydrated(false),
          Select::make('role_id')
            ->label('Role')
            ->options(Role::all()->pluck('name', 'id'))
            ->searchable()
        ])
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('id')->sortable(),
        Tables\Columns\TextColumn::make('name')->searchable(),
        Tables\Columns\TextColumn::make('email')->searchable(),
        Tables\Columns\BadgeColumn::make('role_id')->label('Role')->getStateUsing(
          fn (User $record): string =>
          Role::find($record->role_id)->name
        )->colors(['primary'])
          ->formatStateUsing(fn ($state): string => Str::headline($state))
          ->searchable(),

        Tables\Columns\TextColumn::make('created_at')
          ->dateTime(),
        Tables\Columns\TextColumn::make('updated_at')
          ->dateTime(),
      ])
      ->filters([
        //
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
      'index' => Pages\ListUsers::route('/'),
      'create' => Pages\CreateUser::route('/create'),
      'edit' => Pages\EditUser::route('/{record}/edit'),
    ];
  }
}
