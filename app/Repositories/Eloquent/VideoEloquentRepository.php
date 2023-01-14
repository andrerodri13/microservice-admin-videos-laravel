<?php

namespace App\Repositories\Eloquent;

use App\Models\Video as Model;
use Core\Domain\Entity\Entity;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Repository\VideoRepositoryInterface;

class VideoEloquentRepository implements VideoRepositoryInterface
{
    private Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function insert(Entity $entity): Entity
    {
        // TODO: Implement insert() method.
    }

    public function findById(string $entityId): Entity
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

    public function update(Entity $entity): Entity
    {
        // TODO: Implement update() method.
    }

    public function delete(string $entityId): bool
    {
        // TODO: Implement delete() method.
    }

    public function updateMedia(Entity $entity): Entity
    {
        // TODO: Implement updateMedia() method.
    }
}
