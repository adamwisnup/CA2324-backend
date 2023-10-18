<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Academy extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $keyTyper = 'int';
    protected $table = 'academies';
    protected $increment = true;
    protected $timestamp = true;

    protected $fillable = [
        'name',
        'nim',
        'email',
        'phone_number',
        'document',
        'gender',
        'year_of_enrollment',
        'faculty',
        'major',
        'class'
    ];
}
