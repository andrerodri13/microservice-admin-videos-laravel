<?php

namespace Core\UseCase\Genre;

use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\DTO\Genre\UpdateGenre\GenreUpdateInputDto;
use Core\DTO\Genre\UpdateGenre\GenreUpdateOutputDto;
use Core\UseCase\Interfaces\TransactionInterface;

class UpdateGenreUseCase
{

    private GenreRepositoryInterface $repository;
    private TransactionInterface $transaction;
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(GenreRepositoryInterface $repository, TransactionInterface $transaction, CategoryRepositoryInterface $categoryRepository)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
        $this->categoryRepository = $categoryRepository;
    }

    public function execute(GenreUpdateInputDto $input): GenreUpdateOutputDto
    {
        $genre = $this->repository->findById($input->id);
        try {

            $genre->update(
                name: $input->name,
            );

            foreach ($input->categoriesId as $categoryId) {
                $genre->addCategory($categoryId);
            }

            $this->validateCategoriesId($input->categoriesId);

            $genreDb = $this->repository->update($genre);
            $this->transaction->commit();

            return new GenreUpdateOutputDto(
                id: (string)$genre->id,
                name: $genreDb->name,
                is_active: $genreDb->isActive,
                created_at: $genreDb->createdAt(),
            );
        } catch (\Throwable $th) {
            $this->transaction->rollback();
            throw $th;
        }
    }

    public function validateCategoriesId(array $categoriesId = [])
    {
        $categoriesDb = $this->categoryRepository->getIdsListIds($categoriesId);


        $arrayDiff = array_diff($categoriesId, $categoriesDb);

        if (count($arrayDiff) > 0) {
            $msg = sprintf('%s %s not found',
                count($arrayDiff) > 1 ? 'Categories' : 'Category',
                implode(', ', $arrayDiff)
            );
            throw new NotFoundException($msg);
        }
    }

}
