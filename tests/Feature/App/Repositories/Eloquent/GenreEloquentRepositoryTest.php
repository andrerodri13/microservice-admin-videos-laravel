<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Category;
use App\Models\Genre as GenreModel;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\Domain\Entity\Genre as GenreEntity;
use Core\Domain\Repository\GenreRepositoryInterface;
use Tests\TestCase;

class GenreEloquentRepositoryTest extends TestCase
{

    protected $repository;

    /**
     * Toda vez que executar o teste executara este método primeiro
     */
    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->repository = new GenreEloquentRepository(new GenreModel());
    }

    public function testImplementsInterface()
    {
        $this->assertInstanceOf(GenreRepositoryInterface::class, $this->repository);
    }

    public function testInsert()
    {
        $entity = new GenreEntity(name: 'New Genre');
        $response = $this->repository->insert($entity);

        $this->assertEquals($entity->name, $response->name);
        $this->assertEquals($entity->id, $response->id);
        $this->assertDatabaseHas('genres', [
            'id' => $entity->id()
        ]);
    }

    public function testInsertDeactivate()
    {
        $entity = new GenreEntity(name: 'New Genre');
        $entity->deactivate();

        $this->repository->insert($entity);

        $this->assertDatabaseHas('genres', [
            'id' => $entity->id(),
            'is_active' => false,
        ]);
    }

    public function testInsertWithRelationships()
    {
        $categories = Category::factory()->count(4)->create();

        $genre = new GenreEntity(name: 'teste');
        foreach ($categories as $category) {
            $genre->addCategory($category->id);
        }

        $response = $this->repository->insert($genre);

       $this->assertDatabaseHas('genres', [
           'id' => $response->id(),
       ]);

       $this->assertDatabaseCount('category_genre', 4);
    }


}
