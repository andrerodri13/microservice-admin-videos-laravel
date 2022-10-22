<?php

namespace Core\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\DTO\Genre\DeleteGenre\GenreDeleteOutputDto;
use Core\DTO\Genre\GenreInputDto;

class DeleteGenreUseCase
{

    private GenreRepositoryInterface $repository;

    public function __construct(GenreRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(GenreInputDto $input): GenreDeleteOutputDto
    {
        $success = $this->repository->delete($input->id);
        return new GenreDeleteOutputDto(
            success: $success
        );
    }
}
