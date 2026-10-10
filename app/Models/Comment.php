<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'content',
        'image',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        $cleaned = trim($this->image);
        if (file_exists(public_path('uploads/comments/' . $cleaned))) {
            return asset('uploads/comments/' . $cleaned);
        }

        if (file_exists(public_path('uploads/posts/' . $cleaned))) {
            return asset('uploads/posts/' . $cleaned);
        }

        return asset('uploads/comments/' . $cleaned);
    }
}
