<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttachmentResource\Pages;
use App\Filament\Resources\AttachmentResource\RelationManagers;
use App\Models\Attachment;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttachmentResource extends Resource
{
  protected static ?string $model = Attachment::class;

  protected static ?string $navigationIcon = 'heroicon-o-collection';
  protected static ?string $navigationGroup = 'System Management';
  protected static ?int $navigationSort = 2;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Card::make([
          Select::make('employee_id')->relationship('employee', 'first_name')->searchable()->preload()->required(),
          TextInput::make('name'),
          FileUpload::make('attachment')->preserveFilenames()->enableDownload(),
          Textarea::make('remarks'),
        ])
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('employee.employee_number')->searchable(),
        TextColumn::make('name')->searchable()->limit(50),
        IconColumn::make('attachment')->boolean()->sortable()->default(false),
        TextColumn::make('remarks')->limit(50),
        TextColumn::make('created_at')->dateTime(),
        TextColumn::make('updated_at')->dateTime(),
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
      'index' => Pages\ListAttachments::route('/'),
      'create' => Pages\CreateAttachment::route('/create'),
      'edit' => Pages\EditAttachment::route('/{record}/edit'),
    ];
  }
}
