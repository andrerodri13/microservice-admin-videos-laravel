<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Entity\Genre as EntityGenre;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Core\DTO\Genre\CreateGenre\GenreCreateInputDto;
use Core\DTO\Genre\CreateGenre\GenreCreateOutputDto;
use Core\UseCase\Genre\CreateGenreUseCase;
use Core\UseCase\Interfaces\TransactionInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class CreateGenreUseCaseUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create()
    {
        $uuid = (string) Uuid::uuid4();

        $mockEntity = Mockery::mock(EntityGenre::class, [
            'teste', new ValueObjectUuid($uuid), true, []
        ]);
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('insert')->andReturn($mockEntity);

        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');


        $mockCategoryRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mockCategoryRepository->shouldReceive('getIdsListIds')->andReturn([$uuid]);

        $mockCreateInputDto = Mockery::mock(GenreCreateInputDto::class, [
            'Name', [$uuid], true
        ]);

        $useCase = new CreateGenreUseCase($mockRepository, $mockTransaction, $mockCategoryRepository);
        $response = $useCase->execute($mockCreateInputDto);

        $this->assertInstanceOf(GenreCreateOutputDto::class, $response);
        Mockery::close();
    }
}
