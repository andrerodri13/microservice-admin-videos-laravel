<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\DTO\CastMember\CastMemberInputDto;
use Core\DTO\CastMember\DeleteCastMember\CastMemberDeleteOutputDto;

class DeleteCastMemberUseCase
{

    private CastMemberRepositoryInterface $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CastMemberInputDto $input): CastMemberDeleteOutputDto
    {
        $isDeleted = $this->repository->delete($input->id);

        return new CastMemberDeleteOutputDto(
            success: $isDeleted
        );
    }
}
