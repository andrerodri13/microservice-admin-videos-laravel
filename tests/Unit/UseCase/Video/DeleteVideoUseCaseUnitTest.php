<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Core\UseCase\Video\Delete\DTO\{DeleteInputVideoDTO, DeleteOutputVideoDTO};
use Mockery;
use PHPUnit\Framework\TestCase;
use Core\UseCase\Video\Delete\DeleteVideoUseCase;
use stdClass;

class DeleteVideoUseCaseUnitTest extends TestCase
{


    public function test_delete()
    {
        $useCase = new DeleteVideoUseCase(
            repository: $this->mockRepository()
        );

        $response = $useCase->execute(input: $this->mockInputDTO());

        $this->assertInstanceOf(DeleteOutputVideoDTO::class, $response);
    }

    private function mockRepository()
    {
        $mockRepository = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
        $mockRepository->shouldReceive('delete')
//            ->once()
            ->andReturn(true);

        return $mockRepository;
    }

    private function mockInputDTO()
    {
        return Mockery::mock(DeleteInputVideoDTO::class, [
            Uuid::ramdom()
        ]);
    }


}
