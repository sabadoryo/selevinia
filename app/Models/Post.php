<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'content',
        'category_id',
        'preview_small_image_path',
        'preview_big_image_path',
    ];

    public $hidden = [
        'preview_big_image_path',
        'preview_small_image_path'
    ];

    public $appends = [
        'preview_big_image_url'
    ];

    public $with = [
        'category'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function scopeGlobalSearch(Builder $query, $search)
    {
        return $query->where('id', $search)
            ->orWhere('content', 'LIKE', "%$search%")
            ->orWhere('name', 'LIKE', "%$search%")
            ->orWhereHas('category', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%");
            });
    }

    public function getPreviewBigImageUrlAttribute() {
        return $this->preview_big_image_path ? Storage::disk('public')->url($this->preview_big_image_path) : null;
    }
}
