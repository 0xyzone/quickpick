<?php

namespace App\Filament\Pages;

use App\Models\Company;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Support\Exceptions\Halt;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;

class EditCompany extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $activeNavigationIcon = 'heroicon-m-document-text';
    protected static string $view = 'filament.pages.edit-company';
    protected static ?string $navigationGroup = 'System';

    public function mount(): void
    {
        $company = Company::find(1);
        if ($company) {
            $this->form->fill($company->attributesToArray());
        } else {
            $this->form->fill();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->placeholder('Your company Name')
                    ->required(),
                TextInput::make('address')
                    ->placeholder('Your company Full Address')
                    ->required(),
                TextInput::make('contact')
                    ->label('Contact Number')
                    ->placeholder('Eg. 9801234567/9841234567'),
                TextInput::make('email')
                    ->label('Email Address')
                    ->placeholder('Eg. example@test.com'),
                TextInput::make('pan_number')
                    ->label('PAN Number')
                    ->placeholder('Eg. 123456789'),
                TextInput::make('vat_number')
                    ->label('VAT Number')
                    ->placeholder('Eg. 123456789'),
                    FileUpload::make('company_logo_path')
                    ->image()
                    ->columnSpanFull()

            ])
            ->columns(2)
            ->statePath('data');
    }

    public function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save')
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            $company = Company::find(1);
            if ($company) {
                Company::where('id', 1)->update($data);
            } else {
                Company::create($data);
            }

        } catch (Halt $exception) {
            return;
        }

        Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send();
    }
}
