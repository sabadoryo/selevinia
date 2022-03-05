<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    public $fillable = [
        'full_name',
        'about',
        'articles'
    ];

    public function scopeGlobalSearch(Builder $query, $search)
    {
        return $query->where('id', $search)
            ->orWhere('full_name', 'LIKE', "%$search%")
            ->orWhere('about', 'LIKE', "%$search%")
            ->orWhere('articles', 'LIKE', "%$search%");
    }
}
