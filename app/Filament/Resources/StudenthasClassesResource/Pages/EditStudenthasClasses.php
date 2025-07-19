<?php

namespace App\Filament\Resources\StudenthasClassesResource\Pages;

use App\Filament\Resources\StudenthasClassesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudenthasClasses extends EditRecord
{
    protected static string $resource = StudenthasClassesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
