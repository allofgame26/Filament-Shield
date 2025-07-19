<?php

namespace App\Filament\Resources\TeacherResource\RelationManagers;

use App\Models\Classroom;
use App\Models\Periode;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassroomRelationManager extends RelationManager
{
    protected static string $relationship = 'teacher';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('classrooms_id')
                    ->label("Pilih Kelas")
                    ->options(Classroom::all()->pluck('name','id'))
                    ->searchable()
                    ->relationship('classroom','name')
                    ->createOptionForm([
                        TextInput::make('name')->required()->live()->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', \Str::slug($state))),
                        TextInput::make('slug')
                    ])
                    ->createOptionAction(function (Forms\Components\Actions\Action $action){
                        return $action
                            ->modalHeading('Add Classroom')
                            ->modalButton('Add Classroom')
                            ->modalWidth('3xl');
                    }),
                Select::make('periode_id')
                    ->label('Pilih Periode')
                    ->options(Periode::all()->pluck('name','id'))
                    ->searchable()
                    ->relationship('periode','name')
                    ->createOptionForm([
                        TextInput::make('name')->required(),
                    ])
                    ->createOptionAction(function (Forms\Components\Actions\Action $action){
                        return $action
                            ->modalHeading('Add Periode')
                            ->modalButton('Add Periode')
                            ->modalWidth('3xl');
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('classroom.name'),
                Tables\Columns\TextColumn::make('periode.name'),
                ToggleColumn::make('is_open')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
