<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Category;
use App\Models\Genre as GenreModel;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\Domain\Entity\Genre;
use Core\Domain\Entity\Genre as GenreEntity;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use DateTime;
use Ramsey\Uuid\Uuid as RamseyUuid;
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

    public function testNotFoundById()
    {
        $this->expectException(NotFoundException::class);

        $genge = 'fake_value';

        $this->repository->findById($genge);
    }

    public function testFindById()
    {
        $genge = GenreModel::factory()->create();

        $response = $this->repository->findById($genge->id);

        $this->assertEquals($genge->id, $response->id());
        $this->assertEquals($genge->name, $response->name);
    }

    public function testFindAll()
    {
        $genres = GenreModel::factory()->count(10)->create();

        $genresDb = $this->repository->findAll();

        $this->assertEquals(count($genres), count($genresDb));

    }

    public function testFindAllEmpty()
    {
        $genresDb = $this->repository->findAll();
        $this->assertCount(0, $genresDb);
    }

    public function testFindAllWithFilter()
    {
        GenreModel::factory()->count(10)->create([
            'name' => 'Teste'
        ]);
        GenreModel::factory()->count(10)->create();

        $genresDb = $this->repository->findAll(filter: "Teste");
        $this->assertCount(10, $genresDb);

        $genresDb = $this->repository->findAll();
        $this->assertCount(20, $genresDb);
    }

    public function testPagination()
    {
        $genres = GenreModel::factory()->count(60)->create();

        $response = $this->repository->paginate();

        $this->assertEquals(15, count($response->items()));
        $this->assertEquals(60, $response->total());
    }

    public function testPaginationEmpty()
    {
        $response = $this->repository->paginate();

        $this->assertCount(0, $response->items());
        $this->assertEquals(0, $response->total());
    }

    public function testUpdate()
    {
        $genre = GenreModel::factory()->create();

        $entity = new Genre(
            name: $genre->name,
            id: new Uuid($genre->id),
            isActive: (bool)$genre->is_active,
            createdAt: new DateTime($genre->created_at)
        );

        $nameUpdated = 'Name Updated';
        $entity->update(
            name: $nameUpdated
        );

        $response = $this->repository->update($entity);

        $this->assertEquals($nameUpdated, $response->name);

        $this->assertDatabaseHas('genres', [
            'name' => $nameUpdated
        ]);
    }

    public function testUpdateNotFound()
    {
        $this->expectException(NotFoundException::class);

        $genreId = (string)RamseyUuid::uuid4();

        $entity = new Genre(
            name: 'Name',
            id: new Uuid($genreId),
            isActive: true,
            createdAt: new DateTime(date('Y-m-d H:i:s'))
        );

        $nameUpdated = 'Name Updated';
        $entity->update(
            name: $nameUpdated
        );
        $this->repository->update($entity);
    }

    public function testDeleteNotFound()
    {
        $this->expectException(NotFoundException::class);

        $this->repository->delete('fake_id');
    }

    public function testDelete()
    {
        $genre = GenreModel::factory()->create();
        $response = $this->repository->delete($genre->id);
        $this->assertSoftDeleted('genres', [
            'id' => $genre->id
        ]);
        $this->assertTrue($response);
    }


}
