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
        return $this->belongsTo(YearLevel::class, 'yearLevelId', 'yearLevelId');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'classArmId', 'classArmId');
    }
}
