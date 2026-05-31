<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveClientShowcase extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'video_url',
        'video_title',
        'content_type',
        'feature_tag',
        'objection_type',
        'business_type',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
