<?php

namespace App\Filament\Resources\StudenthasClassesResource\Pages;

use App\Filament\Resources\StudenthasClassesResource;
use App\Models\Classroom;
use App\Models\homerooms;
use App\Models\Periode;
use App\Models\Student;
use App\Models\student_has_classrooms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;

class FormStudentClass extends Page implements HasForms 
{

    use InteractsWithForms;

    protected static string $resource = StudenthasClassesResource::class;

    protected static string $view = 'filament.resources.studenthas-classes-resource.pages.form-student-class';

    public $students  = [];
    public $classrooms = '';
    public $periode = '';

    public function mount(): void{
        $this->form->fill();
    }

    public function getFormSchema(): array 
    {
        return [
                Card::make()
                    ->schema([
                        Select::make('students')
                            ->searchable()
                            ->multiple()
                            ->label('Nama Murid')
                            ->options(Student::all()->pluck('name','id'))
                            ->columnSpan(3),
                        Select::make('classrooms_id')
                            ->searchable()
                            ->label('Kelas')
                            ->options(Classroom::all()->pluck('name','id')),
                        Select::make('periode')
                            ->searchable()
                            ->label('Periode')
                            ->options(Periode::all()->pluck('name','id')),
                    ])
                    ->columns(3)
                ];
    }

    public function save(){
        $students = $this->students;
        $insert = [];
        foreach($students as $row) {
            array_push($insert, [
                'students_id' => $row,
                'classrooms_id' => $this->classrooms,
                'periode_id' => $this->periode,
                'is_open' => 1
            ]);
        }
        student_has_classrooms::insert($insert);

        return redirect()->to('admin/studenthas-classes');
    }
}
