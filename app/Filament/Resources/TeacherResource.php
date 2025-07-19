<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Filament\Resources\TeacherResource\RelationManagers;
use App\Filament\Resources\TeacherResource\RelationManagers\ClassroomRelationManager;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = "Data Guru";

    protected static ?string $navigationGroup = 'Data Akademik';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('nip')->required(),
                        TextInput::make('name')->required(),
                        Textarea::make('address')->required(),
                        FileUpload::make('profile')
                            ->required()
                            ->directory('teachers') //membuat directory, dan jangn lupa untuk command di cmd "php artisan storage:link"
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')->state(
                            static function (HasTable $livewire, stdClass $rowLoop): string {
                                return (string) (
                                    $rowLoop->iteration +
                                    ($livewire->getTableRecordsPerPage() * (
                                        $livewire->getTablePage() - 1
                                    ))
                                );
                            }
                        ),
                TextColumn::make('nip'),
                TextColumn::make('name'),
                TextColumn::make('address')
                    ->toggleable(true),
                ImageColumn::make('profile'), //mengikuti nama FileUpload nya yang dibuat di atas, dan juga jangan lupa mengganti env di APP_URL sesuai dengan IP Address
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
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
            ClassroomRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        $locale = app()->getLocale();

        if($locale == 'id'){
            return "Guru";
        } else {
            return "Teacher";
        }
    }
}
