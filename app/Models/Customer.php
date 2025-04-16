<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//Test push
class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'email'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}