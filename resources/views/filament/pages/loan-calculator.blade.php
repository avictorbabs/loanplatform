{{-- <x-filament-panels::page>
    <div>
        <form wire:submit.prevent="calculateLoan">
            <input type="text" wire:model="name" placeholder="Borrower's Name">
            <input type="number" wire:model="tuitionFees" placeholder="Tuition Fees" required>
            <input type="number" wire:model="hostelFees" placeholder="Hostel Fees" required>
            <input type="number" wire:model="otherCost" placeholder="Other Cost">
            <input type="number" wire:model="costOfLiving" placeholder="Cost of Living">
            <input type="hidden" wire:model="interestRate">
            <input type="number" wire:model="tenure" placeholder="Tenure (in months)">
            <input type="number" wire:model="loanAmount" placeholder="Loan Amount" readonly>
            <button type="submit">Calculate</button>
        </form>

        @if ($monthlyPayment)
            <div>
                <p>Monthly Payment: {{ number_format($monthlyPayment, 2) }}</p>
                <p>Total Payment: {{ number_format($totalPayment, 2) }}</p>
                <p>Total Interest: {{ number_format($totalInterest, 2) }}</p>
            </div>
        @endif

        <button type="button" wire:click="resetForm">Reset</button>
    </div>
</x-filament-panels::page> --}}


<x-filament-panels::page>
    <div>
        <form wire:submit="calculate">
            {{ $this->form }}

            <button type="submit">
                Submit
            </button>
        </form>

        <x-filament-actions::modals />
    </div>
</x-filament-panels::page>
