<?php

namespace App\Filament\Resources\StudenthasClassesResource\Pages;

use App\Filament\Resources\StudenthasClassesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudenthasClasses extends ListRecords
{
    protected static string $resource = StudenthasClassesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
