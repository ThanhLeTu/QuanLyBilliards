<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['table_id', 'customer_name', 'customer_phone', 'start_time', 'end_time'];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
