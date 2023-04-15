<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttachmentsRelationManager extends RelationManager
{
  protected static string $relationship = 'attachments';

  protected static ?string $recordTitleAttribute = 'date';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Card::make([
          Hidden::make('employee_id'),
          TextInput::make('name'),
          FileUpload::make('attachment')->multiple()->preserveFilenames()->enableDownload(),
          Textarea::make('remarks'),
        ])
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('name')->searchable()->limit(50),
        IconColumn::make('attachment')->boolean()->sortable()->default(false),
        TextColumn::make('remarks')->limit(50),
        TextColumn::make('created_at')->dateTime(),
        TextColumn::make('updated_at')->dateTime(),
      ])
      ->filters([
        // Filter::make('attachment')
        //   ->label('Has attachment')
        //   ->query(fn (Builder $query): Builder => $query->where('attachment', '!=', null))
        //   ->query(fn (Builder $query): Builder => $query->where('attachment', '!=', '')),
        // Filter::make('No Attachment')
        //   ->query(fn (Builder $query): Builder => $query->where('attachment', '=', null))
        //   ->query(fn (Builder $query): Builder => $query->where('attachment', '=', '')),
      ])
      ->headerActions([
        Tables\Actions\CreateAction::make(),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\DeleteBulkAction::make(),
      ]);
  }
}
