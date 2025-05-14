<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;

    protected $primaryKey = 'schoolYearId';
    protected $fillable = [
        'yearStart',
        'yearEnd',
        'status',
    ];
}
