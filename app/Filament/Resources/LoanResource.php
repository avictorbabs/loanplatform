<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanResource\Pages;
use App\Filament\Resources\LoanResource\RelationManagers;
use App\Models\Loan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LoanResource extends Resource
{
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
                Forms\Components\TextInput::make('name'),
                Forms\Components\TextInput::make('tuition_fees')->numeric(),
                Forms\Components\TextInput::make('hostel_fees')->numeric(),
                Forms\Components\TextInput::make('other_costs')->numeric(),
                Forms\Components\TextInput::make('cost_of_living')->numeric(),
                Forms\Components\TextInput::make('total_loan_amount')->numeric(),
                Forms\Components\TextInput::make('interest_rate')->numeric(),
                Forms\Components\Select::make('tenure')->label('Tenure Options (months)')->options([ 3 => '3 months', 6 => '6 months', 12 => '12 months', 18 => '18 months'])->required(),
                Forms\Components\TextInput::make('payable_interest_vaule')->numeric(),
                Forms\Components\TextInput::make('monthly_repayment')->numeric(),
                Forms\Components\TextInput::make('total_repayment')->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('tuition_fees')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('hostel_fees')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('other_costs')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('cost_of_living')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('total_loan_amount')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('interest_rate')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('tenure')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('payable_interest_vaule')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('monthly_repayment')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('total_repayment')->numeric()->sortable(),
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
