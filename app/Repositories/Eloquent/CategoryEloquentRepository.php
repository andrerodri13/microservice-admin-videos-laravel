<?php

namespace App\Repositories\Eloquent;

use App\Models\Category as ModelCategory;
use App\Repositories\Presenters\PaginatorPresenter;
use Core\Domain\Entity\Category as Category;
use Core\Domain\Entity\Entity as Entity;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;

class CategoryEloquentRepository implements CategoryRepositoryInterface
{
    protected $model;

    /**
     * CategoryEloquentRepository constructor.
     */
    public function __construct(ModelCategory $category)
    {
        $this->model = $category;
    }

    public function insert(Entity $entityCategory): Entity
    {
        $category = $this->model->create([
            'id' => $entityCategory->id(),
            'name' => $entityCategory->name,
            'description' => $entityCategory->description,
            'is_active' => $entityCategory->isActive,
            'created_at' => $entityCategory->createdAt()
        ]);

        return $this->toCategory($category);
    }

    public function findById(string $categoryId): Category
    {
        if (!$category = $this->model->find($categoryId)) {
            throw new NotFoundException("Category Not Found");
        }

        return $this->toCategory($category);
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $categories = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter)
                    $query->where('name', 'LIKE', "%{$filter}%");
            })
            ->orderBy('id', $order)
            ->get();
        return $categories->toArray();
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        $query = $this->model;
        if ($filter) {
            $query = $query->where('name', 'LIKE', "%{$filter}%");
        }
        $query->orderBy('id', $order);
        $paginator = $query->paginate();

        return new PaginatorPresenter($paginator);
    }

    public function update(Entity $category): Entity
    {
        if (!$categoryDb = $this->model->find($category->id)) {
            throw new NotFoundException("Category not found");
        }

        $categoryDb->update(
            [
                'name' => $category->name,
                'description' => $category->description,
                'is_active' => $category->isActive,
            ]);
        $categoryDb->refresh();

        return $this->toCategory($categoryDb);
    }

    public function delete(string $categoryId): bool
    {
        if (!$categoryDb = $this->model->find($categoryId)) {
            throw new NotFoundException("Category not found");
        }
        return $categoryDb->delete();
    }

    private function toCategory(object $object): Category
    {
        $entity = new Category(
            id: $object->id,
            name: $object->name,
            description: $object->description,
        );
        ((bool)$object->is_active) ? $entity->activate() : $entity->disable();

        return $entity;
    }

    public function getIdsListIds(array $categoriesId = []): array
    {
        return $this->model
            ->whereIn('id', $categoriesId)
            ->pluck('id')
            ->toArray();
    }
}
