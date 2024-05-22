<?php

namespace App\Filament\Resources\LoanResource\Pages;

use App\Filament\Resources\LoanResource;
use App\Models\Loan;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLoan extends CreateRecord
{
    protected static string $resource = LoanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $interestRate = isset($data['interest_rate']) ? ($data['interest_rate'] / 100) : 0.34; // Convert percentage to decimal
        $tenure = $data['tenure_options'] ?? 0; // Ensure tenure is set, default to 0
        //dd($interestRate, $tenure, $data);
        if ($tenure > 0 && $interestRate > 0) {
            // Only perform calculations if both interest rate and tenure are provided
            $monthlyInterestRate = $interestRate / 12;
            // Monthly Payment calculation
            $totalInterest = $data['total_loan_amount'] * $monthlyInterestRate * $tenure;
            $totalRepayment = $data['total_loan_amount'] + $totalInterest;
            $monthlyRepayment = $totalRepayment / $tenure;
            //dd($monthlyRepayment, $totalRepayment, $totalInterest);
            // Add calculated values to the array
            $data['monthly_repayment'] = $monthlyRepayment;
            $data['total_repayment'] = $totalRepayment;
            $data['payable_interest_vaule'] = $totalInterest;
            $data['interest_rate'] = $interestRate;
        } else {
            // Log an error if required data is missing
            logger()->error('Loan calculation failed due to insufficient data', [
                'interest_rate' => $data['interest_rate'],
                'tenure_options' => $data['tenure_options'],
                'total_loan_amount' => $data['total_loan_amount']
            ]);
        }
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
