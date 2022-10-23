<?php

namespace Core\DTO\Genre;

class GenreInputDto extends \Core\DTO\Genre\ListGenre\ListGenresInputDto
{
    /**
     * GenreInputDto constructor.
     */
    public function __construct(
        public string $id = ''
    )
    {
    }
}
