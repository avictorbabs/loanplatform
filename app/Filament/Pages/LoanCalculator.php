<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use App\Models\Loan;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Facades\Validator;


class LoanCalculator extends Page  implements HasForms
{
    use InteractsWithForms; //    Forms\Concerns\InteractsWithForms;

    protected static string $view = 'filament.pages.loan-calculator';

    protected static ?string $model = Loan::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel = 'Loan';
    protected static ?string $pluralModelLabel = 'Loans';
    protected static ?string $navigationLabel = 'Loan';
    protected static ?int $navigationSort = 3;
   //SAVING FIELD TO INDIVIDUAL STATE
    public ?string $name = null;
    public ?string $tuition_fees = null;
    public ?string $hostel_fees = null;
    public ?string $other_costs = null;
    public ?string $cost_of_living = null;
    public ?string $total_loan_amount = null;
    public ?string $interest_rate = null;
    public ?string $tenure_options = null;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Course Details')->schema([
                    Wizard::make([
                        Wizard\Step::make('amount')->label('Loan Amount')
                        // ->afterValidation(function ($livewire) {
                        //     // $totalLoanAmount = array_sum([
                        //     //     $livewire->form->getState('tuition_fees'),
                        //     //     $livewire->form->getState('hostel_fees'),
                        //     //     $livewire->form->getState('other_costs') ?? 0,
                        //     //     $livewire->form->getState('cost_of_living') ?? 0
                        //     // ]);
                        //     // $livewire->form->fill([
                        //     //     'total_loan_amount' => $totalLoanAmount
                        //     // ]);

                        //     $tuition_fees = $livewire->form->getState()['tuition_fees'] ?? 0;
                        //     $hostel_fees = $livewire->form->getState()['hostel_fees'] ?? 0;
                        //     $other_costs = $livewire->form->getState()['other_costs'] ?? 0;
                        //     $cost_of_living = $livewire->form->getState()['cost_of_living'] ?? 0;

                        //     $totalLoanAmount = $tuition_fees + $hostel_fees + $other_costs + $cost_of_living;
                        //     $livewire->form->fill([
                        //         'total_loan_amount' => $totalLoanAmount
                        //     ]);

                        //     logger()->info('Total loan amount calculated: ', ['amount' => $totalLoanAmount]);

                        // })
                        ->schema([
                            Forms\Components\TextInput::make('name'),
                            Forms\Components\TextInput::make('tuition_fees')->numeric()->required(),
                            Forms\Components\TextInput::make('hostel_fees')->numeric()->required(),
                            Forms\Components\TextInput::make('other_costs')->numeric()->required(),
                            Forms\Components\TextInput::make('cost_of_living')->numeric()->required(),
                        ])->columns(columns:2),

                        Wizard\Step::make('tenure')->label('Loan Tenure')->schema([
                            Forms\Components\TextInput::make('total_loan_amount')->numeric(),
                            Forms\Components\TextInput::make('interest_rate')->numeric()->default('34')->hidden(),
                            Forms\Components\Select::make('tenure_options')->label('Tenure Options (months)')->options([ 3 => '3 months', 6 => '6 months', 12 => '12 months', 18 => '18 months'])->required(),
                        ])->columns(columns:2),
                    ])
                ])
        ]);
    }
    // protected function getListeners()
    // {
    //     return [
    //         'updatedTuitionFees',
    //         'updatedHostelFees',
    //         'updatedOtherCost',
    //         'updatedCostOfLiving'
    //     ];
    // }

    // public function updatedTuitionFees($value)
    // {
    //     $this->updateLoanAmount();
    // }

    // public function updatedHostelFees($value)
    // {
    //     $this->updateLoanAmount();
    // }

    // public function updatedOtherCost($value)
    // {
    //     $this->updateLoanAmount();
    // }

    // public function updatedCostOfLiving($value)
    // {
    //     $this->updateLoanAmount();
    // }


    public function updateTotalLoanAmount()
    {
        $this->total_loan_amount = $this->tuition_fees + $this->hostel_fees + $this->other_costs + $this->cost_of_living;
    }

    // public function calculateLoan()
    // {
    //     $monthlyRate = $this->interestRate / 100 / 12;
    //     $this->monthlyPayment = $this->loanAmount * $monthlyRate / (1 - pow(1 + $monthlyRate, -$this->tenure));
    //     $this->totalPayment = $this->monthlyPayment * $this->tenure;
    //     $this->totalInterest = $this->totalPayment - $this->loanAmount;
    // }

    // public function resetForm()
    // {
    //     $this->tuitionFees = 0;
    //     $this->hostelFees = 0;
    //     $this->otherCost = 0;
    //     $this->costOfLiving = 0;
    //     $this->loanAmount = 0;
    //     $this->tenure = null;
    //     $this->name = null;
    //     $this->monthlyPayment = null;
    //     $this->totalPayment = null;
    //     $this->totalInterest = null;
    // }


}
