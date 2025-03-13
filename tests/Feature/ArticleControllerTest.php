<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Article;
use App\Models\Source;
use App\Models\Category;
use App\Models\Author;
use Illuminate\Support\Facades\Crypt;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexMethod()
    {
        // Create test data
        $source = Source::factory()->create(['name' => 'The Guardian']);
        $category = Category::factory()->create(['name' => 'Technology']);
        $author = Author::factory()->create(['name' => 'John Doe']);
        Article::factory()->create([
            'source_id' => $source->id,
            'category_id' => $category->id,
            'author_id' => $author->id,
            'published_at' => '2025-06-15',
        ]);

        // Make a request to the endpoint
        $response = $this->getJson('/api/articles?source=The Guardian&category=Technology&author=John Doe&dateFrom=2025-01-01&dateTo=2025-12-31&per_page=5');

        // Assert the response
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'url',
                    'author',
                    'published_at',
                    'section',
                    'description',
                    'source' => ['id', 'name'],
                    'category' => ['id', 'name'],
                ],
            ],
        ]);
    }

    public function testIndexOneMethod()
    {
        // Create test data
        $source = Source::factory()->create(['name' => 'The Guardian']);
        $category = Category::factory()->create(['name' => 'Technology']);
        $author = Author::factory()->create(['name' => 'John Doe']);
        $article = Article::factory()->create([
            'source_id' => $source->id,
            'category_id' => $category->id,
            'author_id' => $author->id,
            'published_at' => '2025-06-15',
        ]);

        // Make a request to the endpoint
        $response = $this->getJson('/api/fetchone?id=' . Crypt::encrypt($article->id));

        // Assert the response
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'title',
            'url',
            'author',
            'published_at',
            'section',
            'description',
            'source' => ['id', 'name'],
            'category' => ['id', 'name'],
        ]);
    }
}