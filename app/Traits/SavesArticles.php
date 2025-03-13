<?php

namespace App\Traits;

use App\Models\Article;
use App\Models\Author;
use App\Models\Source;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

trait SavesArticles
{
    /**
     * Save articles to the database.
     *
     * @param array $articles
     * @param string $sourceName
     * @return void
     */
    public function saveArticles(array $articles, string $sourceName): void
    {

        try {
            //code...
    
            $source = Cache::rememberForever("source_{$sourceName}", function () use ($sourceName) {
                return Source::firstOrCreate(['name' => $sourceName]);
            });

            $arrInsertArray=[];
            foreach ($articles as $articleData) {
                $category = Cache::rememberForever("category_{$articleData['section']}", function () use ($articleData) {
                    return Category::firstOrCreate(['name' => $articleData['section']]);
                }); 
                $author = Cache::rememberForever("author{$articleData['author']}", function () use ($articleData) {
                    return Author::firstOrCreate(['name' => $articleData['author']]);
                });

                $arrInsertArray[]=
                        [   'url' => $articleData['url'],
                            'title' => $articleData['title'],
                            'author_id' =>$author->id,
                            'published_at' => $articleData['published_at'],
                            'content' => $articleData['description'],
                            'source_id' => $source->id,
                            'category_id' => $category->id,
                        ];
            }

            Article::insert($arrInsertArray);

        } catch (\Throwable $th) {
            Log::error(' API Error: ' . $th->getMessage());
        }
    }
}