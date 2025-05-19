<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $primaryKey = 'enrollmentId';
    protected $fillable = [
        'enrollmentNumber',
        'schoolYearId',
        'yearLevelId',
        'classArmId',
        'studentId',
        'enrollmentType'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'studentId', 'studentId');
    }

    public function yearLevel()
    {
        return $this->belongsTo(YearLevel::class, 'yearLevelId', 'yearLevelId');
    }

    public function classArm()
    {
        return $this->belongsTo(ClassArm::class, 'classArmId', 'classArmId');
    }

    public function billingDiscounts()
    {
        return $this->belongsToMany(BillingDiscount::class, 'billing_discount_enrollment', 'enrollmentId', 'billingDiscountId')->withTimestamps();
    }
}
