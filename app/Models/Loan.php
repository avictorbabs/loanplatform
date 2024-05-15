<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Loan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'tenure',
        'interest_rate',
        'total_loan_amount',
        'cost_of_living',
        'other_costs',
        'hostel_fees',
        'tuition_fees',

        'payable_interest_vaule',
        'monthly_repayment',
        'total_repayment',
    ];
}
