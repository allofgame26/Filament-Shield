<?php

namespace App\Filament\Resources\NilaiResource\Pages;

use App\Filament\Resources\NilaiResource;
use App\Models\CategoryNilai;
use App\Models\Classroom;
use App\Models\Nilai;
use App\Models\Periode;
use App\Models\Student;
use App\Models\Subject;
use Filament\Actions;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateNilai extends CreateRecord
{
    protected static string $resource = NilaiResource::class;

    protected static string $view = 'filament.resources.nilai-resource.pages.store-nilai';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                            Card::make()
                                ->schema([
                                    Select::make('classrooms')
                                        ->options(Classroom::all()->pluck('name', 'id'))
                                        ->required()
                                        ->label('Kelas'),
                                    Select::make('periode')
                                        ->options(Periode::all()->pluck('name', 'id'))
                                        ->required()
                                        ->searchable()
                                        ->label('Periode'),
                                    Select::make('subject_id')
                                        ->options(Subject::all()->pluck('name', 'id'))
                                        ->required()
                                        ->searchable()
                                        ->label('Subject'),
                                    Select::make('category_nilai')
                                        ->options(CategoryNilai::all()->pluck('name', 'id'))
                                        ->required()
                                        ->searchable()
                                        ->label('Kategori Nilai'),
                                ])->columns(3),

                            Repeater::make('nilaistudent')
                                    ->label("Nilai")
                                    ->schema([
                                        Select::make('student')
                                            ->options(Student::all()->pluck('name','id'))
                                            ->label("Murid")
                                            ->searchable(),
                                        TextInput::make('nilai')
                                            ->numeric()
                                    ])->columns(2)
                            ])
                    ]);
    }

    public function save(){
        $get = $this->form->getState();

        $insert = [];

        foreach($get['nilaistudent'] as $row){
            array_push($insert, [
                'class_id' => $get['classrooms'],
                'student_id' => $row['student'],
                'periode_id' => $get['periode'],
                'teacher_id' => Auth::user()->id, // mencatat id login
                'subject_id' => $get['subject_id'],
                'category_nilai_id' => $get['category_nilai'],
                'nilai' => $row['nilai'],
            ]);
        }

        Nilai::insert($insert);

        return redirect()->to('admin/nilais');
    }
}
