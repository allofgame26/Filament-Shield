<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NilaiResource\Pages;
use App\Filament\Resources\NilaiResource\RelationManagers;
use App\Models\CategoryNilai;
use App\Models\Classroom;
use App\Models\Nilai;
use App\Models\Periode;
use App\Models\Student;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NilaiResource extends Resource
{
    protected static ?string $model = Nilai::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                    Select::make('class_id')
                        ->options(Classroom::all()->pluck('name', 'id'))
                        ->required()
                        ->label('Kelas'),
                    Select::make('periode_id')
                        ->options(Periode::all()->pluck('name', 'id'))
                        ->required()
                        ->searchable()
                        ->label('Periode'),
                    Select::make('subject_id')
                        ->options(Subject::all()->pluck('name', 'id'))
                        ->required()
                        ->searchable()
                        ->label('Subject'),
                    Select::make('category_nilai_id')
                        ->options(CategoryNilai::all()->pluck('name', 'id'))
                        ->required()
                        ->searchable()
                        ->label('Kategori Nilai'),
                    Select::make('student_id')
                        ->options(Student::all()->pluck('name', 'id'))
                        ->required()
                        ->searchable()
                        ->label('Murid'),
                    TextInput::make('nilai')
                        ->numeric()
                        ->required()
                        ->label('Nilai'),
                 ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('classroom.name')
                    ->label('Kelas'),
                TextColumn::make('student.name')
                    ->label('Siswa'),
                TextColumn::make('periode.name')
                    ->label('Periode'),
                TextColumn::make('teacher.name')
                    ->label('Guru'),
                TextColumn::make('subject.name')
                    ->label('Mata Pelajaran'),
                TextColumn::make('categoryNilai.name')
                    ->label('Kategori Nilai'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListNilais::route('/'),
            'create' => Pages\CreateNilai::route('/create'),
            'edit' => Pages\EditNilai::route('/{record}/edit'),
        ];
    }
}
