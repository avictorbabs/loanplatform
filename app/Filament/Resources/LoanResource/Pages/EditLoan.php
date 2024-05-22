<?php

namespace App\Filament\Resources\LoanResource\Pages;

use App\Filament\Resources\LoanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoan extends EditRecord
{
    protected static string $resource = LoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        //dd($data);
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

//     protected function mutateFormDataBeforeSave(array $data): array
// {
//     // Apply default values directly in the mutation logic
//     $interestRate = isset($data['interest_rate']) ? ($data['interest_rate'] / 100) : 0.34; // Convert percentage to decimal, default to 34%
//     $tenure = $data['tenure_options'] ?? 18; // Default to 18 months if not provided

//     if ($tenure > 0 && $interestRate > 0) {
//         // Perform calculations if both interest rate and tenure are provided
//         $monthlyInterestRate = $interestRate / 12;
//         $totalInterest = $data['total_loan_amount'] * $monthlyInterestRate * $tenure;
//         $totalRepayment = $data['total_loan_amount'] + $totalInterest;
//         $monthlyRepayment = $totalRepayment / $tenure;

//         // Add calculated values to the array
//         $data['monthly_repayment'] = $monthlyRepayment;
//         $data['total_repayment'] = $totalRepayment;
//         $data['payable_interest_value'] = $totalInterest; // Correct spelling from "vaule" to "value"
//         $data['interest_rate'] = $interestRate * 100; // Store the interest rate as a percentage
//     } else {
//         // Log an error if required data is missing
//         logger()->error('Loan calculation failed due to insufficient data', [
//             'interest_rate' => $data['interest_rate'],
//             'tenure_options' => $data['tenure_options'],
//             'total_loan_amount' => $data['total_loan_amount']
//         ]);
//     }
//     return $data;
// }



}
