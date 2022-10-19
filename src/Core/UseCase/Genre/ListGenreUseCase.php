<?php

namespace Core\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\DTO\Genre\GenreInputDto;
use Core\DTO\Genre\GenreOutputDto;
use Core\DTO\Genre\ListGenre\ListGenresInputDto;
use Core\DTO\Genre\ListGenre\ListGenresOutputDto;

class ListGenreUseCase
{

    private GenreRepositoryInterface $repository;

    public function __construct(GenreRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(GenreInputDto $input): GenreOutputDto
    {
        $genre = $this->repository->findById(id: $input->id);

        return new GenreOutputDto(
            id: (string)$genre->id,
            name: $genre->name,
            is_active: $genre->isActive,
            created_at: $genre->createdAt()
        );
    }
}
