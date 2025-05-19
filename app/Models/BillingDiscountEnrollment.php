<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingDiscountEnrollment extends Model
{
    use HasFactory;

    protected $table = 'billing_discount_enrollment';

    protected $fillable = [
        'enrollmentId',
        'billingDiscountId',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollmentId', 'enrollmentId');
    }

    public function billingDiscount()
    {
        return $this->belongsTo(BillingDiscount::class, 'billingDiscountId', 'billingDiscountId');
    }
}
