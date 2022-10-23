<?php

namespace App\Repositories\Eloquent;

use App\Models\Genre as GenreModel;
use Core\Domain\Entity\Genre as GenreEntity;
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

        if(count($genre->categoriesId) > 0) {
            $genreDb->categories()->sync($genre->categoriesId);
        }

        return $this->toGenre($genreDb);
    }

    public function findById(string $id): GenreEntity
    {
        // TODO: Implement findById() method.
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        // TODO: Implement findAll() method.
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        // TODO: Implement paginate() method.
    }

    public function update(GenreEntity $genre): GenreEntity
    {
        // TODO: Implement update() method.
    }

    public function delete(string $id): bool
    {
        // TODO: Implement delete() method.
    }

    private function toGenre(object $object): GenreEntity
    {
        $entity = new GenreEntity(
            name: $object->name,
            id: new Uuid($object->id),
            createdAt: new DateTime($object->created_at)
        );
        ((bool)$object->is_active) ? $entity->activate() : $entity->deactivate();

        return $entity;
    }
}
