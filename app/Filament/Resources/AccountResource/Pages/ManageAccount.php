<?php

namespace App\Filament\Resources\AccountResource\Pages;

use App\Filament\Resources\AccountResource;
use App\Models\Transaction;
use Exception;
use Filament\Forms\Components\Radio;
use Filament\Resources\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Notifications\Notification;

class ManageAccount extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = []; 

    public $record = null;

    protected static string $resource = AccountResource::class;

    protected static string $view = 'filament.resources.account-resource.pages.manage-account';


    public function create_transaction(): void {
        try{
            $data = $this->form->getState();
            if(auth()->user()->account()->find($data['account_id'])){
                Transaction::create($data);
                Notification::make() 
                    ->success()
                    ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
                    ->send(); 
            }else{
                Notification::make() 
                    ->danger()
                    ->title('Account does not exist')
                    ->send();
            }
        }catch (Exception $err){
            return ;
        }
    }

    public function mount(): void 
    {
        
        $this->form->fill([
            'income' => false,
            'account_id' => $this->record,
        ]);
    }
 
    public function form(Form $form): Form
    {
        return $form
            ->columns([
                'sm' => 1,
                'md' => 2,
            ])
            ->schema([
                Hidden::make('account_id'),
                TextInput::make('value')
                    ->numeric()
                    ->label('Amount')
                    ->minValue(0.01)
                    ->required()
                    ->columnSpan(1),
                TextInput::make('name')
                    ->label('Transaction name')
                    ->columnSpan(1),
                Radio::make('income')
                    ->label('Is this income?')
                    ->boolean()
                    ->inline()
                    ->inlineLabel(false)
                    ->descriptions([
                        true => 'Yes, I recieved this money.',
                        false => 'No, I spent this money.'
                    ])
                    ->default(false),
            ])
            ->statePath('data');
    } 

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save')
                ->submit('test'),
        ];
    }
}
