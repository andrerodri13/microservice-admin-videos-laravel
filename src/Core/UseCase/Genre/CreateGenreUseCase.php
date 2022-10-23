<?php

namespace Core\UseCase\Genre;

use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\Domain\Entity\Genre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\DTO\Genre\CreateGenre\GenreCreateInputDto;
use Core\DTO\Genre\CreateGenre\GenreCreateOutputDto;
use Core\UseCase\Interfaces\TransactionInterface;

class CreateGenreUseCase
{

    /**
     * @var $repository GenreEloquentRepository
    */
    private GenreRepositoryInterface $repository;
    private TransactionInterface $transaction;
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(GenreRepositoryInterface $repository, TransactionInterface $transaction, CategoryRepositoryInterface $categoryRepository)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
        $this->categoryRepository = $categoryRepository;
    }

    public function execute(GenreCreateInputDto $input): GenreCreateOutputDto
    {
        try {
            $genre = new Genre(
                name: $input->name,
                isActive: $input->isActive,
                categoriesId: $input->categoriesId
            );
            $this->validateCategoriesId($input->categoriesId);

            $genreDb = $this->repository->insert($genre);
            $this->transaction->commit();

            return new GenreCreateOutputDto(
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
