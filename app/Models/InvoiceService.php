<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceService extends Model
{
    protected $fillable = ['invoice_id', 'service_id', 'name', 'quantity', 'price'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
