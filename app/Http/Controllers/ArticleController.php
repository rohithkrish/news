<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Traits\FiltersArticles;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    use FiltersArticles;
    public function index(Request $request)
    {

        // Default per page value
        $perPage = $request->query('per_page', 10); // Default to 10 articles per page

        // Fetch query parameters for filtering

        $validator= validator( $request->all(),[
            'dateFrom' => 'nullable|date',
            'dateTo' => 'nullable|date',
            'source' => 'nullable|string',
            'category' => 'nullable|string',
            'keyword' => 'nullable|string',
            'offset' => 'nullable| integer',
            'limit' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
       
        // Paginate the filtered results
        $query = $this->applyFilters($request);
        $articles = $query->paginate($perPage);

        return response()->json($articles);
    }


    public function indexOne()         
    {
        $validator = Validator::make(['id' => request('id')], [
            'id' => 'required|string'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
       try{
        $id= decrypt(request('id'));
        $article = Article::findOrFail($id)->with(['source', 'category', 'author'])->first();
        if (!$article) {
            return response()->json(['message' => 'Article not found'], 202);
        }
        return response()->json($article);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Article not found'], 202);
        }
    }   
}
