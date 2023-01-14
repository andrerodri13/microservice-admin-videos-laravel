<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\Traits\VideoTrait;
use Core\Domain\Builder\Video\UpdateVideoBuilder;
use App\Enums\{ImageTypes, MediaTypes};
use App\Models\Video as Model;
use App\Repositories\Presenters\PaginatorPresenter;
use Core\Domain\Entity\{Entity, Video as VideoEntity};
use Core\Domain\Enum\{MediaStatus, Rating};
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\{PaginationInterface, VideoRepositoryInterface};
use Core\Domain\ValueObject\{Image as ValueObjectImage, Media as ValueObjectMedia};
use Core\Domain\ValueObject\Uuid;

class VideoEloquentRepository implements VideoRepositoryInterface
{
    use VideoTrait;

    public function __construct(protected Model $model)
    {
    }

    public function insert(Entity $entity): Entity
    {
        $entityDb = $this->model->create([
            'id' => $entity->id(),
            'title' => $entity->title,
            'description' => $entity->description,
            'year_launched' => $entity->yearLaunched,
            'rating' => $entity->rating->value,
            'duration' => $entity->duration,
            'opened' => $entity->opened,
        ]);

        $this->syncRelationships($entityDb, $entity);

        return $this->convertObectToEntity($entityDb);

    }

    public function findById(string $entityId): Entity
    {
        if (!$entityDb = $this->model->find($entityId)) {
            throw new NotFoundException('Video not Found');
        }
        return $this->convertObectToEntity($entityDb);
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $result = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('title', 'LIKE', "{$filter}");
                }
            })
            ->orderBy('title', $order)
            ->get();

        return $result->toArray();
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        $result = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('title', 'LIKE', "%{$filter}%");
                }
            })
            ->orderBy('title', $order)
            ->paginate($totalPage, ['*'], 'page', $page);

        return new PaginatorPresenter($result);
    }

    public function update(Entity $entity): Entity
    {
        if (!$entityDb = $this->model->find($entity->id())) {
            throw new NotFoundException('Video not Found');
        }
        $entityDb->update([
            'title' => $entity->title,
            'description' => $entity->description,
            'year_launched' => $entity->yearLaunched,
            'rating' => $entity->rating->value,
            'duration' => $entity->duration,
            'opened' => $entity->opened,
        ]);
        $entityDb->refresh();

        $this->syncRelationships($entityDb, $entity);

        return $this->convertObectToEntity($entityDb);

    }

    public function delete(string $entityId): bool
    {
        if (!$entityDb = $this->model->find($entityId)) {
            throw new NotFoundException('Video not found');
        }
        return $entityDb->delete();
    }

    public function updateMedia(Entity $entity): Entity
    {
        if (!$objectModel = $this->model->find($entity->id())) {
            throw new NotFoundException('Video not found');
        }

        $this->updateMediaVideo($entity, $objectModel);
        $this->updateMediaTrailer($entity, $objectModel);
        $this->updateImageBanner($entity, $objectModel);
        $this->updateImageThumb($entity, $objectModel);
        $this->updateImageThumbHalf($entity, $objectModel);


        return $this->convertObectToEntity($objectModel);
    }

    protected function syncRelationships(Model $model, Entity $entity)
    {
        $model->categories()->sync($entity->categoriesId);
        $model->genres()->sync($entity->genresId);
        $model->castMembers()->sync($entity->castMembersId);
    }

    protected function convertObectToEntity(object $model): VideoEntity
    {
        $entity = new VideoEntity(
            title: $model->title,
            description: $model->description,
            yearLaunched: (int)$model->year_launched,
            duration: (int)$model->duration,
            opened: (bool)$model->opened,
            rating: Rating::from($model->rating),
            id: new Uuid($model->id)
        );
        foreach ($model->categories as $category) {
            $entity->addCategoryId($category->id);
        }
        foreach ($model->genres as $genre) {
            $entity->addGenreId($genre->id);
        }
        foreach ($model->castMembers as $castMember) {
            $entity->addCastMemberId($castMember->id);
        }

        $builder = (new UpdateVideoBuilder())->setEntity($entity);
        if ($trailer = $model->trailer) {
            $builder->addTrailer($trailer->file_path);
        }

        if ($mediaVideo = $model->media) {
            $builder->addMediaVideo(
                path: $mediaVideo->file_path,
                mediaStatus: MediaStatus::from($mediaVideo->media_status),
                encodedPath: $mediaVideo->encoded_path
            );
        }

        if ($banner = $model->banner) {
            $builder->addBanner($banner->path);
        }

        if ($thumb = $model->thumb) {
            $builder->addThumb($thumb->path);
        }

        if ($thumbHalf = $model->thumbHalf) {
            $builder->addThumbHalf($thumbHalf->path);
        }
        return $builder->getEntity();
    }


}
