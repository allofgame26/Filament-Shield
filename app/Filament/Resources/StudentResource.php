<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Infolists\Infolist;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use stdClass;
use Symfony\Component\HttpKernel\Profiler\Profile;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = "Data Murid";

    protected static ?string $navigationGroup = 'Data Akademik';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('nis')->required()
                            ->label('NIS'),
                        TextInput::make('name')->required()->label('Nama'),
                        Select::make('gender')
                            ->label('Jenis Kelamin')
                            ->options([
                                'Male' => 'Laki - Laki',
                                'Female' => 'Perempuan'
                            ])
                            ->required(),
                        DatePicker::make('birthday')
                            ->label('Birthday')
                            ->required(),
                        Select::make('religion')
                            ->options([
                                'Islam' => 'Islam',
                                'Katolik' => 'Katolik',
                                'Protestan' => 'Protestan',
                                'Hindu' => 'Hindu',
                                'Budha' => 'Budha',
                                'Khonghucu' => 'Khonghucu',
                            ])
                            ->label('Agama')
                            ->required(),
                        TextInput::make('contact') 
                                ->label('Nomor Kontak')
                                ->required(),
                        FileUpload::make('profile')
                                ->directory('student')
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
                ImageColumn::make('profile')
                    ->label('Foto Profil')
                    ->disk('public')
                    ->url(fn ($record) => Storage::url($record->profile)),
                TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->toggleable(isToggledHiddenByDefault: True), //untuk menghidden TextColumn
                TextColumn::make('contact')
                    ->label('Kontak'),
                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => ucwords("{$state}")) // membuat huruf kapital
                    ->searchable()
            ])
            ->filters([
                SelectFilter::make('status')
                    ->multiple()
                    ->options([
                        'accept' => 'Accept',
                        'off' => 'Off',
                        'Move' => 'Move',
                        'grade' => 'Grade',
                    ]),
                ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('Ganti Status')
                        ->icon('heroicon-m-check')
                        ->requiresConfirmation()
                        ->form([
                            Select::make('Status')
                                ->label('Change Status')
                                ->options([
                                    'accept' => 'Accept',
                                    'off' => 'Off',
                                    'Move' => 'Move',
                                    'grade' => 'Grade'
                                ])
                                ->required()
                        ])
                        ->action(function (Collection $records, array $data){
                            return $records->each((function ($records) use ($data){
                                $id = $records->id;
                                Student::where('id', $records->$id)->update(['status' => $data['Status']]);
                            }));
                        }),
                    // BulkAction::make('Off')
                    //     ->icon('heroicon-m-check')
                    //     ->requiresConfirmation()
                    //     ->action(function (Collection $records){
                    //         return $records->each((function ($records){
                    //             $id = $records->id;
                    //             Student::where('id', $id)->update(['status' => 'off']);
                    //         }));
                    //     }),
                ]),
            ]);

            // Untuk menaruh Tombol diats List
            // ->headerActions([
            //     CreateAction::make()
            // ]);
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
            'view' => Pages\ViewStudent::route('/{record}')
        ];
    }

    public static function getLabel(): ?string
    {
        $locale = app()->getLocale();

        if($locale == 'id'){
            return "Murid";
        } else {
            return "Student";
        }
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // TextEntry::make('name')
                //     ->label('Nama'),
                // TextEntry::make('nis')
                //     ->label('Nomor Induk Sekolah'),

                Section::make()
                    ->schema([
                        Fieldset::make('Biodata')
                            ->schema([
                                Split::make([
                                    ImageEntry::make('profile')
                                    ->hiddenLabel()
                                    ->grow(false),
                                Grid::make(2)
                                    ->schema([
                                        Group::make([
                                            TextEntry::make('nis'),
                                            TextEntry::make('name'),
                                            TextEntry::make('gender'),
                                            TextEntry::make('birthday'),
                                        ])
                                        ->inlineLabel()
                                        ->columns(1),

                                        Group::make([
                                            TextEntry::make('religion'),
                                            TextEntry::make('contact'),
                                            TextEntry::make('status')
                                            ->badge()
                                            ->color(fn (string $state): string => match($state){
                                                'accept' => 'success',
                                                'off' => 'danger',
                                                'grade' => 'success',
                                                'move' => 'warning',
                                                'wait' => 'gray',
                                            }),
                                            ViewEntry::make('QRCode')
                                            ->view('filament.resources.students.qrcode'),
                                        ])
                                        ->inlineLabel()
                                        ->columns(1),
                                    ])
                                ]) ->from('lg')
                            ])->columns(1)
                    ])->columns(2)
            ]);
    }
}
