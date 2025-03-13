<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\UserPreference;
use Illuminate\Support\Facades\Auth;

class UserPreferenceArticleController extends Controller
{
    /**
     * Fetch articles based on user preferences.
     */
    public function fetchArticles(Request $request)
    {
        try {
            $user = Auth::user();

            // Fetch user preferences
            $preferences = UserPreference::where('user_id', $user->id)->first();

            if (!$preferences) {
                return response()->json(['success' => false, 'message' => 'No preferences found'], 202);
            }

            // Fetch source, categories, and authors
            $sourceIds = $preferences->source()->pluck('id')->toArray();
            $categoryIds = $preferences->categories()->pluck('id')->toArray();
            $authorIds = $preferences->authors()->pluck('id')->toArray();

            // Build the query to fetch articles based on preferences
            $query = Article::with(['source', 'category', 'author']);

            if (!empty($sourceIds)) {
                $query->whereIn('source_id', $sourceIds);
            }

            if (!empty($categoryIds)) {
                $query->whereIn('category_id', $categoryIds);
            }

            if (!empty($authorIds)) {
                $query->whereIn('author_id', $authorIds);
            }

            // Paginate the results
            $perPage = $request->query('per_page', 10); // Default to 10 articles per page
            $articles = $query->paginate($perPage);

            return response()->json(['success' => true, 'articles' => $articles]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching articles.',
                'error' => $e->getMessage(),
            ], 202);
        }
    }
}