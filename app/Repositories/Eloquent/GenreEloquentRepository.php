<?php

namespace App\Repositories\Eloquent;

use App\Models\Genre as GenreModel;
use App\Repositories\Presenters\PaginatorPresenter;
use Core\Domain\Entity\Genre as GenreEntity;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class GenreEloquentRepository implements GenreRepositoryInterface
{
    private GenreModel $model;


    /**
     * GenreEloquentRepository constructor.
     */
    public function __construct(GenreModel $model)
    {
        $this->model = $model;
    }

    public function insert(GenreEntity $genre): GenreEntity
    {
        $genreDb = $this->model->create([
            'id' => $genre->id(),
            'name' => $genre->name,
            'is_active' => $genre->isActive,
            'created_at' => $genre->createdAt()
        ]);

        if (count($genre->categoriesId) > 0) {
            $genreDb->categories()->sync($genre->categoriesId);
        }

        return $this->toGenre($genreDb);
    }

    public function findById(string $id): GenreEntity
    {
        if (!$genreDb = $this->model->find($id)) {
            throw new NotFoundException("Genre {$id} not found");
        }
        return $this->toGenre($genreDb);
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $result = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('name', 'LIKE', "{$filter}");
                }
            })
            ->orderBy('name', $order)
            ->get();

        return $result->toArray();
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        $result = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('name', 'LIKE', "{$filter}");
                }
            })
            ->orderBy('name', $order)
            ->paginate($totalPage);

        return new PaginatorPresenter($result);
    }

    public function update(GenreEntity $genre): GenreEntity
    {
        if (!$genreDb = $this->model->find($genre->id)) {
            throw new NotFoundException("Genre {$genre->id} not found");
        }

        $genreDb->update([
            'name' => $genre->name
        ]);

        if (count($genre->categoriesId) > 0) {
            $genreDb->categories()->sync($genre->categoriesId);
        }

        $genreDb->refresh();

        return $this->toGenre($genreDb);
    }

    public function delete(string $id): bool
    {
        if (!$genreDb = $this->model->find($id)) {
            throw new NotFoundException("Genre {$id} not found");
        }

        return $genreDb->delete();
    }

    private function toGenre(GenreModel $object): GenreEntity
    {
        $entity = new GenreEntity(
            name: $object->name,
            id: new Uuid($object->id),
            createdAt: new DateTime($object->created_at)
        );
        ((bool)$object->is_active) ? $entity->activate() : $entity->deactivate();

        return $entity;
    }

    public function getIdsListIds(array $genresIds = []): array
    {
        return $this->model
            ->whereIn('id', $genresIds)
            ->pluck('id')
            ->toArray();
    }
}
