<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\UserPreferenceArticleController;
use App\Models\Article;
use App\Models\UserPreference;
use Illuminate\Support\Facades\Auth;
use Mockery;

class UserPreferenceArticleControllerTest extends TestCase
{
    protected $userPreferenceArticleController;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userPreferenceArticleController = new UserPreferenceArticleController();
    }

    public function testFetchArticles()
    {
        // Mock the authenticated user
        $user = Mockery::mock('alias:App\Models\User');
        $user->shouldReceive('getAttribute')->with('id')->andReturn(1);
        Auth::shouldReceive('user')->andReturn($user);

        // Mock the UserPreference model
        $preferences = Mockery::mock('alias:App\Models\UserPreference');
        $preferences->shouldReceive('where->first')->andReturn($preferences);
        $preferences->shouldReceive('source->pluck->toArray')->andReturn([1, 2]);
        $preferences->shouldReceive('categories->pluck->toArray')->andReturn([3, 4]);
        $preferences->shouldReceive('authors->pluck->toArray')->andReturn([5, 6]);

        // Mock the Article model
        $articleMock = Mockery::mock('alias:App\Models\Article');
        $articleMock->shouldReceive('with->whereIn->whereIn->whereIn->paginate')
            ->andReturn(collect([]));

        // Create a request
        $request = Request::create('/fetch-articles', 'GET', ['per_page' => 10]);

        // Call the fetchArticles method
        $response = $this->userPreferenceArticleController->fetchArticles($request);

        // Assert the response
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}