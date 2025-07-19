<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudenthasClassesResource\Pages;
use App\Filament\Resources\StudenthasClassesResource\RelationManagers;
use App\Models\homerooms;
use App\Models\Periode;
use App\Models\Student;
use App\Models\student_has_classrooms;
use App\Models\StudenthasClasses;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudenthasClassesResource extends Resource
{
    protected static ?string $model = student_has_classrooms::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('students_id')
                        ->searchable()
                        ->required()
                        ->options(Student::all()->pluck('name','id')),
                        Select::make('homerooms_id')
                        ->searchable()
                        ->required()
                        ->options(homerooms::all()->pluck('classroom.name','id')),
                        Select::make('periode_id')
                        ->searchable()
                        ->required()
                        ->options(Periode::all()->pluck('name','id'))
                    ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('students.name'),
                TextColumn::make('homeroom.classroom.name')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListStudenthasClasses::route('/'),
            // 'create' => Pages\CreateStudenthasClasses::route('/create'),
            'edit' => Pages\EditStudenthasClasses::route('/{record}/edit'),
            'create' => Pages\FormStudentClass::route('/create'),
        ];
    }
}
