<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
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
