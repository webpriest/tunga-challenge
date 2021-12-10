<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'email', 'description', 'interest', 'account', 'checked', 'date_of_birth'];

    protected $casts = [
        'date_of_birth' => 'date'
    ];

    public function credit_card()
    {
        return $this->hasOne(CreditCard::class);
    }
}
