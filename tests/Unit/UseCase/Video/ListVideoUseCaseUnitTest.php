<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video as Entity;
use Core\Domain\Enum\Rating;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Core\UseCase\Video\List\DTO\{ListInputVideoDTO, ListOutpuVideoDTO};
use Core\UseCase\Video\List\ListVideoUseCase;
use Mockery;
use stdClass;
use Tests\TestCase;

class ListVideoUseCaseUnitTest extends TestCase
{

    public function test_list()
    {
        $uuid = Uuid::ramdom();

        $useCase = new ListVideoUseCase(
            repository: $this->mockRepository(),

        );

        $response = $useCase->execute(
            input: $this->mockInputDto($uuid)
        );

        $this->assertInstanceOf(ListOutpuVideoDTO::class, $response);
    }

    private function mockInputDto(string $id)
    {
        return Mockery::mock(ListInputVideoDTO::class, [
            $id
        ]);
    }

    private function mockRepository()
    {
        $mockRepository = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
        $mockRepository->shouldReceive('findById')
            ->once()
            ->andReturn($this->getEntity());

        return $mockRepository;
    }

    private function getEntity(): Entity
    {
        return new Entity(
            title: 'title',
            description: 'desc',
            yearLaunched: 2010,
            duration: 1,
            opened: true,
            rating: Rating::L
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}


