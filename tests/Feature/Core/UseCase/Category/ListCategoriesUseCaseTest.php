<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as ModelCategory;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\DTO\Category\CreateCategory\CategotyCreateInputDto;
use Core\DTO\Category\ListCategories\ListCategoriesInputDto;
use Core\UseCase\Category\ListCategoriesUseCase;
use Tests\TestCase;


class ListCategoriesUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_list_empty()
    {
        $responseUseCase = $this->createUseCase();
        $this->assertCount(0, $responseUseCase->items);

    }

    public function test_list_all()
    {
        $categoriesDb = ModelCategory::factory()->count(20)->create();
        $responseUseCase = $this->createUseCase();
        $this->assertCount(15, $responseUseCase->items);
        $this->assertEquals(count($categoriesDb), $responseUseCase->total);
    }

    private function createUseCase()
    {
        $repository = new CategoryEloquentRepository(new ModelCategory());
        $useCase = new ListCategoriesUseCase($repository);
        return $useCase->execute(new ListCategoriesInputDto());
    }
}
