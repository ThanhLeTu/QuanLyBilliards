<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //
    protected $table = 'invoices'; // tên bảng (nếu khác tên mặc định)

    protected $fillable = [
        'reservation_id',
        'customer_name',
        'customer_phone',
        'payment_method',
        'amount',
        'momo_order_id',
        'momo_trans_id',
        'status',
    ];

    // Quan hệ nếu có bảng reservations
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
    
}
