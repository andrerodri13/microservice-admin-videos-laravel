<?php

namespace Core\UseCase\Video\Create;

use Core\Domain\Entity\Video as Entity;
use Core\Domain\Enum\MediaStatus;
use Core\Domain\Events\VideoCreatedEvent;
use Core\Domain\Repository\{
    CastMemberRepositoryInterface,
    CategoryRepositoryInterface,
    GenreRepositoryInterface,
    VideoRepositoryInterface
};
use Core\Domain\Exception\NotFoundException;
use Core\Domain\ValueObject\Media;
use Core\UseCase\Interfaces\{
    FileStorageInterface,
    TransactionInterface
};
use Core\UseCase\Video\Create\DTO\{
    CreateInputVideoDTO,
    CreateOutputVideoDTO
};
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;
use Throwable;

class CreateVideoUseCase
{

    public function __construct(
        protected VideoRepositoryInterface      $repository,
        protected TransactionInterface          $transaction,
        protected FileStorageInterface          $storage,
        protected VideoEventManagerInterface    $eventManager,

        protected CategoryRepositoryInterface   $repositoryCategory,
        protected GenreRepositoryInterface      $repositoryGenre,
        protected CastMemberRepositoryInterface $repositoryCastMember,
    )
    {
    }

    public function execute(CreateInputVideoDTO $input): CreateOutputVideoDTO
    {
        $entity = $this->createEntity($input);

        try {
            //$repository persistir
            $this->repository->insert($entity);

            //storage da media, usando o $id da entidade persitida
            // -> $eventManager

            if ($pathMedia = $this->storeMedia($entity->id(), $input->videoFile)) {
                $media = new Media(
                    filePath: $pathMedia,
                    mediaStatus: MediaStatus::PROCESSING
                );
                $entity->setVideoFile($media);
                $this->repository->updateMedia($entity);
                $this->eventManager->dispatch(new VideoCreatedEvent($entity));
            }

            //transaction
            $this->transaction->commit();
            return $this->output($entity);

        } catch (Throwable $th) {
            $this->transaction->rollback();
//            if (isset($pathMedia)) $this->storage->delete($pathMedia);
            throw $th;
        }
    }

    private function createEntity(CreateInputVideoDTO $input): Entity
    {
        //create entity -> input
        $entity = new Entity(
            title: $input->title,
            description: $input->description,
            yearLaunched: $input->yearLaunched,
            duration: $input->duration,
            opened: true,
            rating: $input->rating
        );

        //categories_ids in Entity - validate
        $this->validateCategoriesId($input->categories);
        foreach ($input->categories as $categoryId) {
            $entity->addCategoryId($categoryId);
        }
        //genres_ids in Entity - validate
        $this->validateGenresId($input->genres);
        foreach ($input->genres as $genreId) {
            $entity->addGenreId($genreId);
        }
        //cast_members_ids in Entity - validate
        $this->validateCastMembersId($input->castMembers);
        foreach ($input->castMembers as $castMemberId) {
            $entity->addCastMemberId($castMemberId);
        }

        return $entity;
    }

    private function storeMedia(string $path, ?array $media = null): string
    {
        if ($media) {
            return $this->storage->store(
                path: $path,
                file: $media,
            );
        }
        return '';
    }

    private function validateCategoriesId(array $categoriesId = [])
    {
        $categoriesDb = $this->repositoryCategory->getIdsListIds($categoriesId);


        $arrayDiff = array_diff($categoriesId, $categoriesDb);

        if (count($arrayDiff) > 0) {
            $msg = sprintf('%s %s not found',
                count($arrayDiff) > 1 ? 'Categories' : 'Category',
                implode(', ', $arrayDiff)
            );
            throw new NotFoundException($msg);
        }
    }

    private function validateGenresId(array $genresId = [])
    {
        $genresDb = $this->repositoryGenre->getIdsListIds($genresId);


        $arrayDiff = array_diff($genresId, $genresDb);

        if (count($arrayDiff) > 0) {
            $msg = sprintf('%s %s not found',
                count($arrayDiff) > 1 ? 'Genres' : 'Genre',
                implode(', ', $arrayDiff)
            );
            throw new NotFoundException($msg);
        }
    }

    private function validateCastMembersId(array $castMembersId = [])
    {
        $castMembersDb = $this->repositoryGenre->getIdsListIds($castMembersId);


        $arrayDiff = array_diff($castMembersId, $castMembersDb);

        if (count($arrayDiff) > 0) {
            $msg = sprintf('%s %s not found',
                count($arrayDiff) > 1 ? 'Cast Members' : 'Cast Member',
                implode(', ', $arrayDiff)
            );
            throw new NotFoundException($msg);
        }
    }

    private function output(Entity $entity): CreateOutputVideoDTO
    {
        return new CreateOutputVideoDTO(
            id: $entity->id(),
            title: $entity->title,
            description: $entity->description,
            yearLaunched: $entity->yearLaunched,
            duration: $entity->duration,
            opened: $entity->opened,
            rating: $entity->rating,
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
