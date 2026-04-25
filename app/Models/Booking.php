<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'customer_name',
        'field_id',
        'booking_date',
        'start_time',
        'end_time',
        'total_price'
    ];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }
}
