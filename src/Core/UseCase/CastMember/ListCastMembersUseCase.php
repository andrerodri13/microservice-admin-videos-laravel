<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\DTO\CastMember\CastMemberInputDto;
use Core\DTO\CastMember\CastMemberOutputDto;
use Core\DTO\CastMember\ListCastMember\ListCastMembersInputDto;
use Core\DTO\CastMember\ListCastMember\ListCastMembersOutputDto;

class ListCastMembersUseCase
{

    private CastMemberRepositoryInterface $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(ListCastMembersInputDto $input): ListCastMembersOutputDto
    {

        $response = $this->repository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPerPage
        );

        return new ListCastMembersOutputDto(
            items: $response->items(),
            total: $response->total(),
            current_page: $response->currentPage(),
            last_page: $response->lastPage(),
            first_page: $response->firstPage(),
            per_page: $response->perPage(),
            to: $response->to(),
            from: $response->from()
        );
    }
}
