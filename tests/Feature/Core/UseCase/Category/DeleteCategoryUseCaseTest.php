<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as ModelCategory;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\DTO\Category\CategoryInputDto;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Tests\TestCase;

class DeleteCategoryUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_delete()
    {
        $categoryDb = ModelCategory::factory()->create();

        $repository = new CategoryEloquentRepository(new ModelCategory());
        $useCase = new DeleteCategoryUseCase($repository);
        $useCase->execute(
            new CategoryInputDto(
                id: $categoryDb->id
            )
        );
        $this->assertSoftDeleted($categoryDb);
    }
}
