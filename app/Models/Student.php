<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $primaryKey = 'studentId';
    protected $fillable = [
        'lrn',
        'firstName',
        'middleName',
        'lastName',
        'suffix',
        'profilePhoto'
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'studentId', 'studentId');
    }
}
