<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as ModelCategory;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\DTO\Category\CreateCategory\CategotyCreateInputDto;
use Core\UseCase\Category\CreateCategoryUseCase;
use Tests\TestCase;

class CreateCategoryUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create()
    {
        $repository = new CategoryEloquentRepository(new ModelCategory());
        $useCase = new CreateCategoryUseCase($repository);
        $responseUseCase = $useCase->execute(
            new CategotyCreateInputDto(
                name: 'Teste'
            )
        );

        $this->assertEquals('Teste', $responseUseCase->name);
        $this->assertNotEmpty($responseUseCase->id);

        $this->assertDatabaseHas('categories', [
            'id' => $responseUseCase->id
        ]);
    }
}
