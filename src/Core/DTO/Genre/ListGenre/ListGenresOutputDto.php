<?php

namespace Core\DTO\Genre\ListGenre;

class ListGenresOutputDto
{
    /**
     * ListCategoriesOutputDto constructor.
     * injeta atributos semelhantes aos metodos da Interface PaginationInterface.
     */
    public function __construct(
        public array $items,
        public int $total,
        public int $current_page,
        public int $last_page,
        public int $first_page,
        public int $per_page,
        public int $to,
        public int $from,
    )
    {
    }
}
