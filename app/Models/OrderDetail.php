<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

     /**
     * Get the user for the order operation log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
