<?php


namespace Core\DTO\CastMember\UpdateCastMember;


class CastMemberUpdateInputDto
{
    public function __construct(
        public string $id,
        public string $name,
    ) {}
}
