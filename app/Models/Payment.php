<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'paymentId';

    protected $fillable = [
        'enrollmentId',
        'paymentDate',
        'receiptNumber',
        'billingConfigId',
        'amountPaid',
        'paymentMethod',
        'notes',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollmentId');
    }

    public function billingConfiguration()
    {
        return $this->belongsTo(BillingConfiguration::class, 'billingConfigId');
    }

}
