<?php

namespace App\Filament\Resources\LoanResource\Pages;

use App\Filament\Resources\LoanResource;
use App\Models\Loan;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLoan extends CreateRecord
{
    protected static string $resource = LoanResource::class;

    protected function afterSubmit(): void
    {
        $data = $this->form->getState();
        $monthlyRate = $data['interest_rate'] / 100 / 12;
        $monthlyPayment = $data['total_loan_amount'] * $monthlyRate / (1 - pow(1 + $monthlyRate, -$data['tenure_options']));
        $totalPayment = $monthlyPayment * $data['tenure_options'];
        $totalInterest = $totalPayment - $data['total_loan_amount'];

        // Save to the database
        Loan::create([
            'name' => $data['name'],
            'total_loan_amount' => $data['total_loan_amount'],
            'interest_rate' => $data['interest_rate'],
            'tenure_options' => $data['tenure_options'],
            'monthly_payment' => $monthlyPayment,
            'total_payment' => $totalPayment,
            'total_interest' => $totalInterest
        ]);
    }
}
