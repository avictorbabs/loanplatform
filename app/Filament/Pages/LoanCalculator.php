<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use App\Models\Post;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Filament\Forms\Components\Wizard;


class LoanCalculator extends Page  implements HasForms
{
     use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.loan-calculator';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public $tuitionFees = 0;
    public $hostelFees = 0;
    public $otherCost = 0;
    public $costOfLiving = 0;
    public $loanAmount = 0;
    public $interestRate = 34; // Hidden default interest rate
    public $tenure;
    public $name; // Borrower's name
    public $monthlyPayment;
    public $totalPayment;
    public $totalInterest;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Loan Amount')->schema([
                        Forms\Components\TextInput::make('name'),
                        Forms\Components\TextInput::make('tuition_fees')->numeric(),
                        Forms\Components\TextInput::make('hostel_fees')->numeric(),
                        Forms\Components\TextInput::make('other_costs')->numeric(),
                        Forms\Components\TextInput::make('cost_of_living')->numeric(),
                    ]),
                    Wizard\Step::make('Loan Tenure')->schema([
                        Forms\Components\TextInput::make('total_loan_amount')->numeric(),
                        Forms\Components\TextInput::make('interest_rate')->numeric(),
                        Forms\Components\Select::make('tenure')->label('Tenure Options (months)')->options([ 3 => '3 months', 6 => '6 months', 12 => '12 months', 18 => '18 months'])->required(),
                    ]),
                ])
            ])->columns(columns:2)
            ->statePath('data');
    }

    public function calculate(): void
    {
        LoanCalculator::create($this->form->getState());
    }

    protected function getListeners()
    {
        return [
            'updatedTuitionFees',
            'updatedHostelFees',
            'updatedOtherCost',
            'updatedCostOfLiving'
        ];
    }

    public function updatedTuitionFees($value)
    {
        $this->updateLoanAmount();
    }

    public function updatedHostelFees($value)
    {
        $this->updateLoanAmount();
    }

    public function updatedOtherCost($value)
    {
        $this->updateLoanAmount();
    }

    public function updatedCostOfLiving($value)
    {
        $this->updateLoanAmount();
    }

    public function updateLoanAmount()
    {
        $this->loanAmount = $this->tuitionFees + $this->hostelFees + $this->otherCost + $this->costOfLiving;
    }

    public function calculateLoan()
    {
        $monthlyRate = $this->interestRate / 100 / 12;
        $this->monthlyPayment = $this->loanAmount * $monthlyRate / (1 - pow(1 + $monthlyRate, -$this->tenure));
        $this->totalPayment = $this->monthlyPayment * $this->tenure;
        $this->totalInterest = $this->totalPayment - $this->loanAmount;
    }

    public function resetForm()
    {
        $this->tuitionFees = 0;
        $this->hostelFees = 0;
        $this->otherCost = 0;
        $this->costOfLiving = 0;
        $this->loanAmount = 0;
        $this->tenure = null;
        $this->name = null;
        $this->monthlyPayment = null;
        $this->totalPayment = null;
        $this->totalInterest = null;
    }
}
