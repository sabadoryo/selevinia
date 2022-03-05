<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Archive extends Model
{
    use HasFactory;

    public $fillable = [
        'title',
        'description',
        'year',
        'tome',
        'document_path',
        'preview_small_image_path',
        'preview_big_image_path'
    ];

    public $hidden = [
        'preview_big_image_path',
        'preview_small_image_path',
        'document_path'
    ];

    public $appends = [
        'preview_big_image_url',
        'document_url'
    ];

    public function getPreviewBigImageUrlAttribute() {
        return $this->preview_big_image_path ? Storage::disk('public')->url($this->preview_big_image_path) : null;
    }

    public function getDocumentUrlAttribute() {
        return $this->document_path ? Storage::disk('public')->url($this->document_path) : null;
    }

    public function scopeGlobalSearch(Builder $query, $search)
    {
        return $query->where('id', $search)
            ->orWhere('title', 'LIKE', "%$search%")
            ->orWhere('description', 'LIKE', "%$search%");
    }
}
