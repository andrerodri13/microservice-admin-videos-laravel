<?php


namespace Core\DTO\Category\UpdateCategories;


class CategoryUpdateOutputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $description = '',
        public bool $isActive = true,
        public string $created_at = '',
    ) {}
}