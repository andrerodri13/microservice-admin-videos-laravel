<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\DTO\CastMember\CreateCastMember\CastMemberCreateInputDto;
use Core\DTO\CastMember\CreateCastMember\CastMemberCreateOutputDto;

class CreateCastMemberUseCase
{


    private CastMemberRepositoryInterface $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CastMemberCreateInputDto $input): CastMemberCreateOutputDto
    {
        $entity = new CastMember(
            name: $input->name,
            type: $input->type == 1 ? CastMemberType::DIRECTOR : CastMemberType::ACTOR
        );

        $this->repository->insert($entity);

        return new CastMemberCreateOutputDto(
            id: $entity->id(),
            name: $entity->name,
            type: $input->type,
            created_at: $entity->createdAt(),
        );
    }
}
