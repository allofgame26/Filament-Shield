<?php

namespace App\Livewire;

use App\Models\Student;
use App\Models\User;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Attributes\Session;
use Livewire\Component;

class Home extends Component implements HasForms
{
    use InteractsWithForms;

    public $nis = '';
    public $name = '';
    public $gender = '';
    public $birthday = '';
    public $religion = '';
    public $contact = '';
    public $profile;

    public function form(Form $form): Form
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
                        TextInput::make('profile')
                                ->type('file')
                                ->extraAttributes(['class' => 'rounded'])
                    ])->columns(2)
            ]);
    }

    public function render()
    {
        return view('livewire.home');
    }

    public function save(){

        $data = ($this->form->getState());

        if ($this->profile){
            $uploadFile = $this->profile;
            $fileName = time() . '_' . $uploadFile->getClientOriginalName();
            $path = $uploadFile->storeAs('public/students', $fileName);

            $data['profile'] = 'student/'.$fileName;

            Student::insert($data);

            Notification::make()
                ->success()
                ->title('Murid'. $this->name. ' Telah Mendaftar')
                ->sendToDatabase(User::whereHas('roles', function ($query) {
                    $query->where('name','superadmin');
                }));

            Session()->flash('message','Save berhasil');
        }
        // Student::create([
        //     'nis' => $this->nis,
        //     'name' => $this->name,
        //     'gender' => $this->gender,
        //     'birthday' => $this->birthday,
        //     'religion' => $this->religion,
        //     'contact' => $this->contact,
        //     'profile' => $this->profile,
        // ]);
    }

    public function delSession():void{
        session()->forget('message');
        $this->nis = '';
        $this->name = '';
        $this->gender = '';
        $this->birthday = '';
        $this->religion = '';
        $this->contact = '';
        $this->profile = null;
    }
}
