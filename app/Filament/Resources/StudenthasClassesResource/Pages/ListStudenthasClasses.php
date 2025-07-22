<?php

namespace App\Filament\Resources\StudenthasClassesResource\Pages;

use App\Filament\Resources\StudenthasClassesResource;
use App\Models\Classroom;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListStudenthasClasses extends ListRecords
{
    protected static string $resource = StudenthasClassesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array{
        $data = [];

        $classrooms = Classroom::orderBy('name')->get();

        foreach($classrooms as $class){
            $data[$class -> name] = Tab::make()->modifyQueryUsing(fn ($query) => $query->where('classrooms_id', $class->id)->where('is_open',true));
        }

        return $data;
    }
}
