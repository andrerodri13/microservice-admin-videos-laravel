<?php

namespace Core\DTO\Genre\UpdateGenre;

class GenreUpdateInputDto
{

    public function __construct (
        public string $id,
        public string $name,
        public array $categoriesId = [],
    )
    {
    }


}
