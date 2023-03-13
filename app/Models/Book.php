<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'title',
        'cover',
        'author',
        'shelf_id',
        'category_id',
        'stock'
    ];

    public function shelf() {
        return $this->belongsTo(Shelf::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
