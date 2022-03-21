<?php


namespace Core\UseCase\Category;


use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\DTO\Category\CategoryInputDto;
use Core\DTO\Category\DeleteCategory\CategoryDeleteOutputDto;

class DeleteCategoryUseCase
{
    protected $repository;

    /**
     * DeleteCategoryUseCase constructor.
     */
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CategoryInputDto $input): CategoryDeleteOutputDto
    {
       $responseDelete = $this->repository->delete($input->id);

       return new CategoryDeleteOutputDto(
           success: $responseDelete
       );

    }
}