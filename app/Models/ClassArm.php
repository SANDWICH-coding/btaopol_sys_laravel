<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassArm extends Model
{
    use HasFactory;

    protected $primaryKey = 'classArmId';
    protected $fillable = [
        'schoolYearId',
        'yearLevelId',
        'className',
    ];

    public function yearLevel()
    {
        return $this->belongsTo(YearLevel::class, 'yearLevelId');
    }
}
