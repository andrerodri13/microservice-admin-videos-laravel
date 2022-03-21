<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as ModelCategory;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\DTO\Category\CategoryInputDto;
use Core\UseCase\Category\ListCategoryUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListCategoryUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_list()
    {
        $categoriesDb = ModelCategory::factory()->create();

        $repository = new CategoryEloquentRepository(new ModelCategory());
        $useCase = new ListCategoryUseCase($repository);
        $responseUseCase = $useCase->execute(new CategoryInputDto(
            id: $categoriesDb->id
        ));

        $this->assertEquals($categoriesDb->id, $responseUseCase->id);
        $this->assertEquals($categoriesDb->name, $responseUseCase->name);
        $this->assertEquals($categoriesDb->description, $responseUseCase->description);
    }
}
