<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'booking_date',
        'notes',
    ];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
