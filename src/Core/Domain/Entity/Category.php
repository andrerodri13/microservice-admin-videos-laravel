<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicsTrait;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class Category extends Entity
{
    /**
     * Category constructor.
     */
    public function __construct(
        protected Uuid|string     $id = '',
        protected string          $name = '',
        protected string          $description = '',
        protected bool            $isActive = true,
        protected DateTime|string $createdAt = ''
    )
    {
        $this->id = $this->id ? new Uuid($this->id) : Uuid::ramdom();
        $this->createdAt = $this->createdAt ? new DateTime($this->createdAt) : new DateTime();
        $this->validate();
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function disable(): void
    {
        $this->isActive = false;
    }

    public function update(string $name, string $description = '')
    {
        $this->name = $name;
        $this->description = $description;
        $this->validate();
    }

    private function validate()
    {
        DomainValidation::strMaxLength($this->name);
        DomainValidation::strMinLength($this->name);
        DomainValidation::strCanNullMaxLength($this->description);
    }


}
