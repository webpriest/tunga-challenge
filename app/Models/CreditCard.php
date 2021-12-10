<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditCard extends Model
{
    use HasFactory;

    protected $fillable = ['profile_id', 'name', 'type', 'number', 'expirationDate'];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
