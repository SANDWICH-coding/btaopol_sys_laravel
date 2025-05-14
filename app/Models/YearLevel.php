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

    public function classArms()
    {
        return $this->hasMany(ClassArm::class, 'yearLevelId', 'yearLevelId');
    }


}
