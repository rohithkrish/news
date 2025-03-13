<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\ArticleController;
use App\Models\Article;
use Mockery;

class ArticleControllerTest extends TestCase
{
    protected $articleController;

    protected function setUp(): void
    {
        parent::setUp();

        $this->articleController = new ArticleController();
    }

    public function testIndexMethod()
    {
        // Mock the Request
        $request = Request::create('/articles', 'GET', [
            'source' => 'The Guardian',
            'category' => 'Technology',
            'author' => 'John Doe',
            'dateFrom' => '2025-01-01',
            'dateTo' => '2025-12-31',
            'per_page' => 5,
        ]);

        // Mock the Article model
        $articleMock = Mockery::mock('alias:App\Models\Article');
        $articleMock->shouldReceive('with->whereHas->whereHas->whereHas->whereDate->whereDate->paginate')
            ->andReturn(collect([]));

        // Create an instance of the controller
        $controller = new ArticleController();

        // Call the index method
        $response = $controller->index($request);

        // Assert the response
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testIndexOneMethod()
    {
        // Mock the Request
        $request = Request::create('/fetchone', 'GET', ['id' => encrypt(1)]);

        // Mock the Article model
        $articleMock = Mockery::mock('alias:App\Models\Article');
        $articleMock->shouldReceive('with->findOrFail')
            ->andReturn((object) ['id' => 1, 'title' => 'Test Article']);

        // Create an instance of the controller
        $controller = new ArticleController();

        // Call the indexOne method
        $response = $controller->indexOne($request);

        // Assert the response
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}