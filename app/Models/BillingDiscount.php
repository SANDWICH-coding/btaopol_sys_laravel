<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'billingDiscountId',
        'schoolYearId',
        'discountName',
        'discountDiscription',
        'discountType',
        'discountValue',
        'appliesTo'
    ];
    protected $primaryKey = 'billingDiscountId';

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class, 'schoolYearId');
    }

    public function enrollments()
    {
        return $this->belongsToMany(Enrollment::class, 'billing_discount_enrollment', 'billingDiscountId', 'enrollmentId')->withTimestamps();
    }

}
