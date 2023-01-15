<?php

namespace Core\UseCase\Video\List;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\List\DTO\{ListInputVideoDTO, ListOutpuVideoDTO};

class ListVideoUseCase
{

    public function __construct(
        private VideoRepositoryInterface $repository,

    )
    {
    }

    public function execute(ListInputVideoDTO $input): ListOutpuVideoDTO
    {
        $entity = $this->repository->findById($input->id);

        return new ListOutpuVideoDTO(
            id: $entity->id(),
            title: $entity->title,
            description: $entity->description,
            yearLaunched: $entity->yearLaunched,
            duration: $entity->duration,
            opened: $entity->opened,
            rating: $entity->rating,
            createdAt: $entity->createdAt(),
            categories: $entity->categoriesId,
            genres: $entity->genresId,
            castMembers: $entity->castMembersId,
            videoFile: $entity->videoFile()?->filePath, //? - se o videoFile for null nao da o erro no filePath
            trailerFile: $entity->trailerFile()?->filePath,
            thumbFile: $entity->thumbFile()?->path(),
            thumbHalf: $entity->thumbHalf()?->path(),
            bannerFile: $entity->bannerFile()?->path(),
        );
    }
}
