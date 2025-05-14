<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingItem extends Model
{
    use HasFactory;

    protected $primaryKey = "billingItemId";
    protected $fillable = [
        'billItem',
        'category',
        'remarks',
    ];

    public function billingConfigurations()
    {
        return $this->hasMany(BillingConfiguration::class, 'billingItemId', 'billingItemId');
    }
}
