<?php

namespace App\Filament\App\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view                   = 'filament.app.pages.profile';
    protected static ?string $navigationIcon        = 'heroicon-o-user';
    protected static bool $shouldRegisterNavigation = false;
    public ?array $data                             = [];

    public function mount(): void
    {
        $user = Auth::user();

        $this->form->fill([
            'name'   => $user->name,
            'email'  => $user->email,
            'detail' => [
                'avatar'    => $user->detail->avatar ?? null,
                'phone'     => $user->detail->phone ?? null,
                'gender'    => $user->detail->gender ?? null,
                'id_card'   => $user->detail->id_card ?? null,
                'birthdate' => $user->detail->birthdate ?? null,
                'address'   => $user->detail->address ?? null,
            ]
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Utama')
                    ->schema([
                        FileUpload::make('detail.avatar')
                            ->label('Foto Profil')
                            ->directory('avatars')
                            ->disk('public')
                            ->visibility('public')
                            ->image()
                            ->name('avatarField')
                            ->avatar()
                            ->imageEditor()
                            ->columnSpanFull(),
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required(),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                    ])
                    ->columns(2),
                Section::make('Detail Tambahan')
                    ->schema([
                        TextInput::make('detail.phone')
                            ->label('Nomor HP')
                            ->tel()
                            ->regex('/^(\+62|62|0)8[1-9][0-9]{6,9}$/'),
                        Select::make('detail.gender')
                            ->label('Jenis Kelamin')
                            ->options([
                                'male'   => 'Laki-laki',
                                'female' => 'Perempuan',
                            ]),
                        TextInput::make('detail.id_card')
                            ->label('ID Card'),
                        DatePicker::make('detail.birthdate')
                            ->label('Tanggal Lahir')
                            ->maxDate(now()),
                        Textarea::make('detail.address')
                            ->label('Alamat Lengkap')
                            ->rows(3),
                    ])
                    ->columns(2)
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        $user = Auth::user();

        // Update user
        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);

        // Update atau create user detail
        $user->detail()->updateOrCreate([], [
            'avatar'    => $data['detail']['avatar'],
            'phone'     => $data['detail']['phone'],
            'gender'    => $data['detail']['gender'],
            'id_card'   => $data['detail']['id_card'],
            'birthdate' => $data['detail']['birthdate'],
            'address'   => $data['detail']['address'],
        ]);

        Notification::make()
            ->title('Update Profile Success!')
            ->success()
            ->send();
    }
}
