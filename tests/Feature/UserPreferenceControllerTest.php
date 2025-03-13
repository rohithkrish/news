<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\UserPreference;
use App\Models\Source;
use App\Models\Category;
use App\Models\Author;
use Illuminate\Support\Facades\Auth;

class UserPreferenceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetPreferences()
    {
        // Create test data
        $user = User::factory()->create();
        $source = Source::factory()->create(['name' => 'The Guardian']);
        $category = Category::factory()->create(['name' => 'Technology']);
        $author = Author::factory()->create(['name' => 'John Doe']);
        $userPreference = UserPreference::factory()->create(['user_id' => $user->id]);
        $userPreference->sources()->attach($source->id);
        $userPreference->categories()->attach($category->id);
        $userPreference->authors()->attach($author->id);

        // Mock the authenticated user
        Auth::shouldReceive('user')->andReturn($user);

        // Make a request to the endpoint
        $response = $this->getJson('/api/get-preferences');

        // Assert the response
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'preferences' => [
                'sources',
                'categories',
                'authors',
            ],
        ]);
    }

    public function testSetPreferences()
    {
        // Create test data
        $user = User::factory()->create();
        $source = Source::factory()->create(['name' => 'The Guardian']);
        $category = Category::factory()->create(['name' => 'Technology']);
        $author = Author::factory()->create(['name' => 'John Doe']);

        // Mock the authenticated user
        Auth::shouldReceive('user')->andReturn($user);

        // Make a request to the endpoint
        $response = $this->postJson('/api/set-preferences', [
            'sources' => ['The Guardian'],
            'categories' => ['Technology'],
            'authors' => ['John Doe'],
        ]);

        // Assert the response
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'preferences' => [
                'sources',
                'categories',
                'authors',
            ],
        ]);
    }
}