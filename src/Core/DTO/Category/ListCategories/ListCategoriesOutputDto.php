<?php


namespace Core\DTO\Category\ListCategories;


class ListCategoriesOutputDto
{
    /**
     * ListCategoriesOutputDto constructor.
     * injeta atributos semelhantes aos metodos da Interface PaginationInterface.
     */
    public function __construct(
        public array $items,
        public int $total,
        public int $last_page,
        public int $first_page,
        public int $to,
        public int $from,
    )
    {
    }
}