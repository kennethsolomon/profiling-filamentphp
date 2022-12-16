<?php

namespace App\Filament\Resources\AttachmentResource\Pages;

use App\Filament\Resources\AttachmentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttachment extends EditRecord
{
    protected static string $resource = AttachmentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
