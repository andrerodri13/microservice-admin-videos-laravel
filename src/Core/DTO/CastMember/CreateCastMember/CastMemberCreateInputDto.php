<?php

namespace Core\DTO\CastMember\CreateCastMember;

class CastMemberCreateInputDto
{


    public function __construct(
        public string $name,
        public int    $type
    )
    {
    }
}
