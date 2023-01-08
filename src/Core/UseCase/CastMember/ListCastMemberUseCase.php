<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\DTO\CastMember\CastMemberInputDto;
use Core\DTO\CastMember\CastMemberOutputDto;

class ListCastMemberUseCase
{

    private CastMemberRepositoryInterface $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CastMemberInputDto $input): CastMemberOutputDto
    {
        $castMember = $this->repository->findById($input->id);

        return new CastMemberOutputDto(
            id: $castMember->id(),
            name: $castMember->name,
            type: $castMember->type->value,
            createdAt: $castMember->createdAt()
        );
    }
}
