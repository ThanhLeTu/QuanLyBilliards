<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'status'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    // Accessor để lấy URL đầy đủ của hình ảnh
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/services/' . $this->image);
        }
        return asset('images/default-service.png'); // Đường dẫn ảnh mặc định, bạn có thể cần tạo thư mục public/images
    }

    // Scope để lọc theo danh mục
    public function scopeCategory($query, $category)
    {
        if ($category) {
            return $query->where('category', $category);
        }
        return $query;
    }

    public function reservationServices()
    {
        return $this->hasMany(ReservationService::class);
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_services')
            ->withPivot(['quantity', 'price'])
            ->withTimestamps();
    }
}