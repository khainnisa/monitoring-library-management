<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'bio'];

    // Relasi: 1 author punya banyak buku
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}

