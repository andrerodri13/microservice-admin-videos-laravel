<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as ModelCategory;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\DTO\Category\UpdateCategories\CategoryUpdateInputDto;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Tests\TestCase;

class UpdateCategoryUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_update()
    {
        $categoryDb = ModelCategory::factory()->create();

        $repository = new CategoryEloquentRepository(new ModelCategory());
        $useCase = new UpdateCategoryUseCase($repository);
        $responseUseCase = $useCase->execute(
            new CategoryUpdateInputDto(
                id: $categoryDb->id,
                name: 'Name Updated'
            )
        );

        $this->assertEquals('Name Updated', $responseUseCase->name);
        $this->assertEquals($categoryDb->description, $responseUseCase->description);
        $this->assertDatabaseHas('categories', [
            'name' => $responseUseCase->name
        ]);

    }

}
