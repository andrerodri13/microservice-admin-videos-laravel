<?php

namespace Tests\Unit\App\Http\Controllers\Api;

use App\Http\Controllers\Api\CategoryController;
use Core\DTO\Category\ListCategories\ListCategoriesOutputDto;
use Core\UseCase\Category\ListCategoriesUseCase;
use Illuminate\Http\Request;
use Mockery;
use PHPUnit\Framework\TestCase;

class CategoryControllerUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $mockRequest = Mockery::mock(Request::class);
        $mockRequest->shouldReceive('get')->andReturn('teste');

        $mockDtoOutput = Mockery::mock(ListCategoriesOutputDto::class, [
            [], 1, 1, 1, 1, 1, 1, 1
        ]);

        $mockDtoOutput->shouldReceive('execute')->andReturn('teste');

        $mockUseCase = Mockery::mock(ListCategoriesUseCase::class);
        $mockUseCase->shouldReceive('execute')->andReturn($mockDtoOutput);

        $controller = new CategoryController();
        $response = $controller->index($mockRequest, $mockUseCase);

        $this->assertIsObject($response->resource);
        $this->assertArrayHasKey('meta', $response->additional);

        /**
         * spies
         */
        $mockUseCaseSpy = Mockery::spy(ListCategoriesUseCase::class);
        $mockUseCaseSpy->shouldReceive('execute')->andReturn($mockDtoOutput);
        $controller->index($mockRequest, $mockUseCaseSpy);
        $mockUseCaseSpy->shouldHaveReceived('execute');

        Mockery::close();
    }
}
