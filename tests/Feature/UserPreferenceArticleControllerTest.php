<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Article;
use App\Models\User;
use App\Models\UserPreference;
use App\Models\Source;
use App\Models\Category;
use App\Models\Author;
use Illuminate\Support\Facades\Auth;

class UserPreferenceArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testFetchArticles()
    {
        // Create test data
        $user = User::factory()->create();
        $source = Source::factory()->create();
        $category = Category::factory()->create();
        $author = Author::factory()->create();
        $article = Article::factory()->create([
            'source_id' => $source->id,
            'category_id' => $category->id,
            'author_id' => $author->id,
        ]);

        // Create user preferences
        $userPreference = UserPreference::factory()->create(['user_id' => $user->id]);
        $userPreference->sources()->attach($source->id);
        $userPreference->categories()->attach($category->id);
        $userPreference->authors()->attach($author->id);

        // Mock the authenticated user
        Auth::shouldReceive('user')->andReturn($user);

        // Make a request to the endpoint
        $response = $this->getJson('/api/fetch-articles?per_page=10');

        // Assert the response
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'articles' => [
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
            ],
        ]);
    }
}