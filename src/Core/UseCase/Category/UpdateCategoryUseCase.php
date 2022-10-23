<?php

namespace Core\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\DTO\Category\UpdateCategories\CategoryUpdateInputDto;
use Core\DTO\Category\UpdateCategories\CategoryUpdateOutputDto;

class UpdateCategoryUseCase
{
    protected $repository;

    /**
     * UpdateCategoryUseCase constructor.
     */
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CategoryUpdateInputDto $input): CategoryUpdateOutputDto
    {
        $category = $this->repository->findById($input->id);
        $category->update(
            name: $input->name,
            description: $input->description ?? $category->description,
        );
        ((bool)$input->isActive) ? $category->activate() : $category->disable();


        $categoryUpdated = $this->repository->update($category);

        return new CategoryUpdateOutputDto(
            id: $categoryUpdated->id,
            name: $categoryUpdated->name,
            description: $categoryUpdated->description,
            is_active: $categoryUpdated->isActive,
            created_at: $categoryUpdated->createdAt(),
        );
    }


}
