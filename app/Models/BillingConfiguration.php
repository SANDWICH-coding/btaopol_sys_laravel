<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingConfiguration extends Model
{
    use HasFactory;

    protected $primaryKey = "billingConfigId";
    protected $fillable = [
        'schoolYearId',
        'yearLevelId',
        'billingItemId',
        'amount',
        'isRequired',
    ];

    public function billingItems()
    {
        return $this->belongsTo(BillingItem::class, 'billingItemId', 'billingItemId');
    }

    public function yearLevels()
    {
        return $this->belongsTo(YearLevel::class, 'yearLevelId', 'yearLevelId');
    }

    public function schoolYears()
    {
        return $this->belongsTo(SchoolYear::class, 'schoolYearId', 'schoolYearId');
    }
}
