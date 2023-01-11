<?php

namespace Core\Domain\Validation;

use Core\Domain\Entity\Entity;

interface ValidationInterface
{
    public function validate(Entity $entity): void;
}
