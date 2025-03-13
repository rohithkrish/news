<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\UserPreferenceController;
use App\Models\UserPreference;
use App\Models\Source;
use App\Models\Category;
use App\Models\Author;
use Illuminate\Support\Facades\Auth;
use Mockery;

class UserPreferenceControllerTest extends TestCase
{
    protected $userPreferenceController;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userPreferenceController = new UserPreferenceController();
    }

    public function testGetPreferences()
    {
        // Mock the authenticated user
        $user = Mockery::mock('alias:App\Models\User');
        $user->shouldReceive('getAttribute')->with('id')->andReturn(1);
        Auth::shouldReceive('user')->andReturn($user);

        // Mock the UserPreference model
        $preferences = Mockery::mock('alias:App\Models\UserPreference');
        $preferences->shouldReceive('where->first')->andReturn($preferences);
        $preferences->shouldReceive('categories->pluck')->andReturn(collect(['Technology']));
        $preferences->shouldReceive('authors->pluck')->andReturn(collect(['John Doe']));
        $preferences->shouldReceive('source->pluck')->andReturn(collect(['The Guardian']));

        // Create a request
        $request = Request::create('/get-preferences', 'GET');

        // Call the getPreferences method
        $response = $this->userPreferenceController->getPreferences($request);

        // Assert the response
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testSetPreferences()
    {
        // Mock the authenticated user
        $user = Mockery::mock('alias:App\Models\User');
        $user->shouldReceive('getAttribute')->with('id')->andReturn(1);
        Auth::shouldReceive('user')->andReturn($user);

        // Mock the Source, Category, and Author models
        Source::shouldReceive('whereIn->pluck->toArray')->andReturn([1]);
        Category::shouldReceive('whereIn->pluck->toArray')->andReturn([1]);
        Author::shouldReceive('whereIn->pluck->toArray')->andReturn([1]);

        // Mock the UserPreference model
        UserPreference::shouldReceive('updateOrCreate')->andReturn(true);

        // Create a request
        $request = Request::create('/set-preferences', 'POST', [
            'sources' => ['The Guardian'],
            'categories' => ['Technology'],
            'authors' => ['John Doe'],
        ]);

        // Call the setPreferences method
        $response = $this->userPreferenceController->setPreferences($request);

        // Assert the response
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}