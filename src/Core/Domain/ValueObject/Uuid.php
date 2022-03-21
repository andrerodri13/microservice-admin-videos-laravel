<?php

namespace Core\Domain\ValueObject;

use http\Exception\InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid
{

    /**
     * Uuid constructor.
     */
    public function __construct(
        protected string $value
    )
    {
        $this->ensureIsValid($value);
    }

    public static function ramdom(): self
    {
        return new self(RamseyUuid::uuid4()->toString());
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function ensureIsValid(string $id)
    {
        if (!RamseyUuid::isValid($id))
            throw new InvalidArgumentException(sprintf('<%s> does not allow the value <%s>.', static::class, $id));
    }
}