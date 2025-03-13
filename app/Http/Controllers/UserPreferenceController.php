<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPreference;
use App\Models\Source;
use App\Models\Category;
use App\Models\Author;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends Controller
{
    /**
     * Get the authenticated user's preferences.
     */
    public function getPreferences()
    {
        try {
            $user = Auth::user();

            // Fetch preferences along with source, categories, and authors
            $preferences = UserPreference::where('user_id', $user->id)
                ->first();

            if (!$preferences) {
                return response()->json(['success' => false, 'message' => 'No preferences found'], 202);
            }

            // Fetch source, categories, and authors
                $categories = $preferences->categories();
               $authors = $preferences->authors();
                $sources = $preferences->source();
            

            return response()->json([
                'success' => true,
                'preferences' => [
                   'source' => $sources->pluck('name'),
                    'categories' => $categories->pluck('name'),
                    'authors' => $authors->pluck('name'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving preferences.',
                'error' => $e->getMessage(),
            ], 202);
        }    
    }

    /**
     * Update or create user preferences.
     */
    public function setPreferences(Request $request)
    {
        $user = Auth::user();
    
        // Validate incoming request
        $validatedData = $request->validate([
            'sources' => 'nullable|array',
            'sources.*' => 'string|exists:sources,name',
            'categories' => 'nullable|array',
            'categories.*' => 'string|exists:categories,name',
            'authors' => 'nullable|array',
            'authors.*' => 'string|exists:authors,name',
        ], [
            'sources.*.exists' => 'One or more sources are invalid.',
            'categories.*.exists' => 'One or more categories are invalid.',
            'authors.*.exists' => 'One or more authors are invalid.',
        ]);
    
        try {
            // Fetch existing source
            $sourceIds = Source::whereIn('name', $validatedData['sources'] ?? [])->pluck('id')->toArray(); // Fetch existing source IDs
    
            // Fetch existing category IDs
            $categoryIds = Category::whereIn('name', $validatedData['categories'] ?? [])->pluck('id')->toArray();
    
            // Fetch existing author IDs
            $authorIds = Author::whereIn('name', $validatedData['authors'] ?? [])->pluck('id')->toArray();
            \Log::info('Category IDs:', ['category_ids' => $categoryIds]);
            // Update or create user preferences
            $preferences = UserPreference::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'source_id' => $sourceIds, // Assign source ID only if it exists
                    'category_ids' => $categoryIds, // Assign existing category IDs
                    'author_ids' => $authorIds, // Assign existing author IDs
                ]
            );
    
            return response()->json([
                'success' => true,
                'message' => 'Preferences updated successfully.',
                'preferences' => [
                    'sources' => Source::whereIn('id', $sourceIds)->pluck('name'),
                    'categories' => Category::whereIn('id', $categoryIds)->pluck('name'),
                    'authors' => Author::whereIn('id', $authorIds)->pluck('name'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating preferences.',
                'error' => $e->getMessage(),
            ], 202);
        }
    }
    


}
