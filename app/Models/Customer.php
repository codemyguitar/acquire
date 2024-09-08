<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'first_name',
        'last_name',
        'age',
        'dob',
        'email',
        'creation_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
