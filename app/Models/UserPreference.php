<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'source_id', 'category_ids', 'author_ids'];

    protected $casts = [
        'category_ids' => 'array',
        'author_ids' => 'array',
        'source_id' => 'array',
    ];
    // Relationship with Categories
    public function categories()
    {
        return Category::whereIn('id', $this->category_ids);
    }

    public function authors()
    {
        return Author::whereIn('id', $this->author_ids);
    }
    public function source()
    {
        return Source::whereIn('id', $this->source_id);
    }

}
