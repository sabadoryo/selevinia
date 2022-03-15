<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Publication extends Model
{
    use HasFactory;

    public $fillable = [
        'title',
        'description',
        'image_path',
        'document_path'
    ];

    public $hidden = [
        'image_path',
        'document_path'
    ];

    public $appends = [
        'image_url',
        'document_url'
    ];

    public function getImageUrlAttribute() {
        return $this->image_path ? Storage::disk('public')->url($this->image_path) : null;
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
