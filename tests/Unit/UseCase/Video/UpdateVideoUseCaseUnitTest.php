<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\ValueObject\Uuid;
use Core\UseCase\Video\Update\DTO\{UpdateInputVideoDTO, UpdateOutputVideoDTO};
use Core\UseCase\Video\Update\UpdateVideoUseCase;

class UpdateVideoUseCaseUnitTest extends BaseVideoUseCaseUnitTest
{

    public function test_exec_input_output()
    {
        $this->createUseCase();
        $response = $this->useCase->execute(
            input: $this->createMockInputDTO(),
        );

        $this->assertInstanceOf(UpdateOutputVideoDTO::class, $response);
    }

    protected function nameActionRepository(): string
    {
        return 'update';
    }

    protected function getUseCase(): string
    {
        return UpdateVideoUseCase::class;
    }

    protected function createMockInputDTO(
        array  $categoriesIds = [],
        array  $genresIds = [],
        array  $castMembersIds = [],
        ?array $videoFile = null,
        ?array $trailerFile = null,
        ?array $thumbFile = null,
        ?array $thumbHalf = null,
        ?array $bannerFile = null,
    )
    {
        return \Mockery::mock(UpdateInputVideoDTO::class, [
            Uuid::ramdom(),
            'title',
            'desc',
            $categoriesIds,
            $genresIds,
            $castMembersIds,
            $videoFile,
            $trailerFile,
            $thumbFile,
            $thumbHalf,
            $bannerFile,
        ]);
    }
}
