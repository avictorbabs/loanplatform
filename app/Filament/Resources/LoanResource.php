<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanResource\Pages;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Loan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Support\Facades\Log;

class LoanResource extends Resource
{
    use CreateRecord\Concerns\HasWizard;

    protected static ?string $model = Loan::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel = 'Loan';
    protected static ?string $pluralModelLabel = 'Loans';
    protected static ?string $navigationLabel = 'Loan';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Course Details')->schema([
                    Wizard::make([
                        Wizard\Step::make('amount')->label('Loan Amount')
                        ->schema([
                            Forms\Components\TextInput::make('name'),
                            Forms\Components\TextInput::make('tuition_fees')->numeric()->required(),
                            Forms\Components\TextInput::make('hostel_fees')->numeric()->required(),
                            Forms\Components\TextInput::make('other_costs')->numeric()->required(),
                            Forms\Components\TextInput::make('cost_of_living')->numeric()->required(),
                        ])->columns(columns:2)
                        ->afterStateUpdated(function ($state, $set, $get) {
                            // Calculate total loan amount when any field updates
                            $tuition_fees = floatval($get('tuition_fees') ?? 0);
                            $hostel_fees = floatval($get('hostel_fees') ?? 0);
                            $other_costs = floatval($get('other_costs') ?? 0);
                            $cost_of_living = floatval($get('cost_of_living') ?? 0);

                            $totalLoanAmount = $tuition_fees + $hostel_fees + $other_costs + $cost_of_living;
                            // Log the computed value
                            logger()->info('Computed total loan amount: ', ['amount' => $totalLoanAmount]);
                            // Set the computed value
                            $set('total_loan_amount', $totalLoanAmount);
                        }),
                        Wizard\Step::make('tenure')->label('Loan Tenure')->schema([
                            Forms\Components\TextInput::make('total_loan_amount')->numeric()->required()->default(fn ($get) => $get('total_loan_amount')),
                            Forms\Components\TextInput::make('interest_rate')->numeric()->default('34')->hidden()->required(),
                            Forms\Components\Select::make('tenure_options')->label('Tenure Options (months)')->options([ 3 => '3 months', 6 => '6 months', 12 => '12 months', 18 => '18 months'])->required()->default(18),
                            Forms\Components\TextInput::make('payable_interest_vaule')->visibleOn('view'),
                            Forms\Components\TextInput::make('monthly_repayment')->visibleOn('view'),
                            Forms\Components\TextInput::make('total_repayment')->visibleOn('view'),
                        ])->columns(columns:2)
                        // ->afterStateUpdated(function ($state, $set, $get) {
                        //     $totalLoanAmount = floatval($get('total_loan_amount'));
                        //     $interestRate = floatval($get('interest_rate'));
                        //     $tenureMonths = floatval($get('tenure_options'));

                        //     // $monthlyInterestRate = $interestRate / 12 / 100;
                        //     // $monthlyRepayment = $totalLoanAmount * $monthlyInterestRate * pow(1 + $monthlyInterestRate, $tenureMonths) / (pow(1 + $monthlyInterestRate, $tenureMonths) - 1);
                        //     // $totalRepayment = $monthlyRepayment * $tenureMonths;
                        //     // $payableInterestValue = $totalRepayment - $totalLoanAmount;

                        //     // $set('monthly_repayment', round($monthlyRepayment, 2));
                        //     // $set('total_repayment', round($totalRepayment, 2));
                        //     // $set('payable_interest_value', round($payableInterestValue, 2));

                        //     // Log::info('Loan calculation details', [
                        //     //     'total_loan_amount' => $totalLoanAmount,
                        //     //     'monthly_repayment' => round($monthlyRepayment, 2),
                        //     //     'total_repayment' => round($totalRepayment, 2),
                        //     //     'payable_interest_value' => round($payableInterestValue, 2)
                        //     //]);
                        // })
                    ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('tuition_fees')->numeric()->label('Tuition Fees')->sortable(),
                Tables\Columns\TextColumn::make('hostel_fees')->label('Hostel Fees')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('other_costs')->label('Other Costs')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('cost_of_living')->label('Cost of Living')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('total_loan_amount')->numeric()->label('Total Loan Amount')->sortable(),
                Tables\Columns\TextColumn::make('interest_rate')->numeric()->label('Interest Rate')->sortable(),
                Tables\Columns\TextColumn::make('tenure_options')->numeric()->label('Tenure')->sortable(),
                Tables\Columns\TextColumn::make('payable_interest_vaule')->label('Service Charge')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('monthly_repayment')->numeric()->label('Monthly Repayment')->sortable(),
                Tables\Columns\TextColumn::make('total_repayment')->label('Total Repayment')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListLoans::route('/'),
            'create' => Pages\CreateLoan::route('/create'),
            'view' => Pages\ViewLoan::route('/{record}'),
            'edit' => Pages\EditLoan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
