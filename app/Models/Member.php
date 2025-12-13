<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'membership_number',
        'name',
        'email',
        'phone',
        'address',
        'join_date',
        'status',
    ];

    protected $casts = [
        'join_date' => 'date',
    ];

    // Relationship
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
}