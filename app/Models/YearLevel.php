<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearLevel extends Model
{
    use HasFactory;

    protected $primaryKey = 'yearLevelId';
    protected $fillable = [
        'yearLevelName',
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'yearLevelId', 'yearLevelId');
    }

    public function classArms()
    {
        return $this->hasMany(ClassArm::class, 'yearLevelId', 'yearLevelId');
    }


}
