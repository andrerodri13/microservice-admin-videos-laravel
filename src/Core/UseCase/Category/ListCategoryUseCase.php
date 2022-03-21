<?php

namespace Core\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\DTO\Category\CategoryInputDto;
use Core\DTO\Category\CategoryOutputDto;

class ListCategoryUseCase
{
    protected $repository;

    /**
     * ListCategoryUseCase constructor.
     */
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CategoryInputDto $input) : CategoryOutputDto
    {
        $category = $this->repository->findById($input->id);
        return new CategoryOutputDto(
            id: $category->id,
            name: $category->name,
            description: $category->description,
            is_active: $category->isActive,
            created_at: $category->createdAt()
        );
    }
}