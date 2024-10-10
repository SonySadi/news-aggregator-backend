<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsArticle extends Model
{
    protected $fillable = [
        'source_name',
        'source_id',
        'author',
        'title',
        'abstract',
        'content',
        'url',
        'url_to_image',
        'published_at',
        'keywords',
        'section_name',
        'news_type',
        'word_count',
        'document_type',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'keywords' => 'array',
    ];
}
