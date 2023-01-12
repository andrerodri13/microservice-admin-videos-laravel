<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video as Entity;
use Core\Domain\Enum\Rating;
use Core\Domain\Repository\{
    CastMemberRepositoryInterface,
    CategoryRepositoryInterface,
    GenreRepositoryInterface,
    VideoRepositoryInterface
};
use Core\UseCase\Interfaces\{
    FileStorageInterface,
    TransactionInterface
};
use Core\UseCase\Video\Create\CreateVideoUseCase;
use Core\UseCase\Video\Create\DTO\{
    CreateInputVideoDTO,
    CreateOutputVideoDTO
};
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class CreateVideoUseCaseTest extends TestCase
{
    protected $useCase;

    protected function setUp(): void
    {
        $this->useCase = new CreateVideoUseCase(
            repository: $this->createMockRepository(),
            transaction: $this->createMockTransaction(),
            storage: $this->createMockFileStorage(),
            eventManager: $this->createMockEventManager(),

            repositoryCategory: $this->createMockRepositoryCategory(),
            repositoryGenre: $this->createMockRepositoryGenre(),
            repositoryCastMember: $this->createMockRepositoryCastMembers(),
        );

        parent::setUp();
    }

    public function test_exec_input_output()
    {
        $response = $this->useCase->execute(
            input: $this->createMockInputDTO(),
        );

        $this->assertInstanceOf(CreateOutputVideoDTO::class, $response);
    }

    private function createMockRepository()
    {
        $mockRepository = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
        $mockRepository->shouldReceive('insert')
            ->andReturn($this->createMockEntity());
        $mockRepository->shouldReceive('updateMedia');
        return $mockRepository;
    }

    private function createMockRepositoryCategory(array $categoriesResponse = [])
    {
        $mockRepositoryCategory = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mockRepositoryCategory->shouldReceive('getIdsListIds')->andReturn($categoriesResponse);
        return $mockRepositoryCategory;
    }

    private function createMockRepositoryGenre(array $genresResponse = [])
    {
        $mockRepositoryGenre = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepositoryGenre->shouldReceive('getIdsListIds')->andReturn($genresResponse);
        return $mockRepositoryGenre;
    }

    private function createMockRepositoryCastMembers(array $castMembersResponse = [])
    {
        $mockRepositoryCastMember = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepositoryCastMember->shouldReceive('getIdsListIds')->andReturn($castMembersResponse);
        return $mockRepositoryCastMember;
    }

    private function createMockTransaction()
    {
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');
        return $mockTransaction;
    }

    private function createMockFileStorage()
    {
        $mockFileStorage = Mockery::mock(stdClass::class, FileStorageInterface::class);
        $mockFileStorage->shouldReceive('store')
            ->andReturn('path/file.png');
        return $mockFileStorage;
    }

    private function createMockEventManager()
    {
        $mockEventManager = Mockery::mock(stdClass::class, VideoEventManagerInterface::class);
        $mockEventManager->shouldReceive('dispatch');
        return $mockEventManager;
    }

    private function createMockInputDTO()
    {
        return Mockery::mock(CreateInputVideoDTO::class, [
            'title',
            'description',
            2023,
            12,
            true,
            Rating::RATE10,
            [],
            [],
            [],
        ]);
    }

    private function createMockEntity()
    {
        return Mockery::mock(Entity::class, [
            'title',
            'description',
            2026,
            60,
            true,
            Rating::RATE10,
        ]);

    }


}
