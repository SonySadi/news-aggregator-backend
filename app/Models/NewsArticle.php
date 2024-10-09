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
        'subsection_name',
        'news_type',
        'multimedia',
        'word_count',
        'document_type',
        'byline',
        'pillar_id',
        'pillar_name',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'keywords' => 'array',
        'multimedia' => 'array',
        'byline' => 'array',
    ];
}
