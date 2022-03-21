<?php

namespace Core\DTO\Category\CreateCategory;

class CategotyCreateInputDto
{

    public function __construct (
        public string $name,
        public string $description = '',
        public bool $isActive = true
    )
    {
    }


}