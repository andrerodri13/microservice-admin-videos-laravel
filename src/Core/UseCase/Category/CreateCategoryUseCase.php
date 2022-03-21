<?php

namespace Core\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\DTO\Category\CreateCategory\CategotyCreateInputDto;
use Core\DTO\Category\CreateCategory\CategotyCreateOutputDto;

class CreateCategoryUseCase
{
    protected $repository;

    /**
     * CreateCategoryUseCase constructor.
     */
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CategotyCreateInputDto $input): CategotyCreateOutputDto
    {
        $category = new Category(
            name: $input->name,
            description: $input->description,
            isActive: $input->isActive
        );

        $newCategory = $this->repository->insert($category);

        return new CategotyCreateOutputDto(
            id: $newCategory->id(),
            name: $newCategory->name,
            description: $category->description,
            is_active: $category->isActive,
            created_at: $newCategory->createdAt()
        );
    }
}