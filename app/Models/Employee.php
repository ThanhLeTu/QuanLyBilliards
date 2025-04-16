<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
     // Khai báo các trường được phép ghi (mass assign)
     protected $fillable = [
        'name',
        'email',
        'phone',
        'position',
        'gender',
        'birth_date',
        'start_date',
        'avatar',
        'citizen_id_image',
        'salary_per_month',
    ];
    //format ngày 
    protected $dates = [
        'birth_date',
        'start_date',
        'created_at',
        'updated_at',
    ];
}