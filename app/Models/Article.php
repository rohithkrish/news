<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Article extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'url', 'author_id', 'source_id', 'published_at'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

