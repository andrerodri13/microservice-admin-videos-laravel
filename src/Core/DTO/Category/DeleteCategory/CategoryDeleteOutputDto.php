<?php


namespace Core\DTO\Category\DeleteCategory;


class CategoryDeleteOutputDto
{
    public function __construct(
        public bool $success
    ) {}
}