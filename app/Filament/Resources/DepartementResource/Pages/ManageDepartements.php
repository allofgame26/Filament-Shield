<?php

namespace App\Filament\Resources\DepartementResource\Pages;

use App\Filament\Resources\DepartementResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDepartements extends ManageRecords
{
    protected static string $resource = DepartementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    //function mengganti nama label / title
    public function getTitle(): string {
        return "Departement";
    }
}
