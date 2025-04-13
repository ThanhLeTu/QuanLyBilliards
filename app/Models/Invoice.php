<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'customer_name',
        'customer_phone',
        'customer_note',
        'table_name',
        'start_time',
        'end_time',
        'play_time_minutes',
        'table_price',
        'play_cost',
        'services_cost',
        'total_payment',
    ];
    

    // Quan hệ với model Service (1 hóa đơn có nhiều dịch vụ)
    public function services()
    {
        return $this->hasMany(Service::class);  // Đây là quan hệ 1-nhiều
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function usedServices()
    {
        return $this->hasMany(InvoiceService::class);
    }
    
    // Trong model Invoice
public function getServicesAttribute()
{
    return $this->usedServices;
}


}

