<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\Article;

trait FiltersArticles
{
    /**
     * Apply filters to the article query.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyFilters(Request $request)
    {
        $sourceName = $request->source;
        $categoryName = $request->category;
        $authorName = $request->author;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Build the query to fetch articles with eager loading
        $query = Article::with(['source', 'category', 'author']);

        // Apply filters if provided
        if ($sourceName) {
            $query->whereHas('source', function ($q) use ($sourceName) {
                $q->where('name', $sourceName);
            });
        }

        if ($categoryName) {
            $query->whereHas('category', function ($q) use ($categoryName) {
                $q->where('name', $categoryName);
            });
        }

        if ($authorName) {
            $query->whereHas('author', function ($q) use ($authorName) {
                $q->where('name', $authorName);
            });
        }

        if ($startDate) {
            $query->whereDate('published_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('published_at', '<=', $endDate);
        }

        return $query;
    }

}  