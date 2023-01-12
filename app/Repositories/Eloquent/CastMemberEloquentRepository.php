<?php

namespace App\Repositories\Eloquent;

use App\Models\CastMember as CastMemberModel;
use App\Repositories\Presenters\PaginatorPresenter;
use Core\Domain\Entity\CastMember as Entity;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Illuminate\Database\Eloquent\Model;

class CastMemberEloquentRepository implements CastMemberRepositoryInterface
{
    private CastMemberModel $model;

    public function __construct(CastMemberModel $model)
    {
        $this->model = $model;
    }

    public function insert(Entity $castMember): Entity
    {
        $dataDb = $this->model->create([
            'id' => $castMember->id(),
            'name' => $castMember->name,
            'type' => $castMember->type->value,
            'created_at' => $castMember->createdAt()
        ]);

        return $this->convertToEntity($dataDb);
    }

    public function findById(string $castMemberId): Entity
    {
        if (!$dataDb = $this->model->find($castMemberId)) {
            throw new NotFoundException("Cast Member {$castMemberId} Not Found");
        }

        return $this->convertToEntity($dataDb);
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $dataDb = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter)
                    $query->where('name', 'LIKE', "%{$filter}%");
            })
            ->orderBy('name', $order)
            ->get();

        return $dataDb->toArray();
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        $query = $this->model;
        if ($filter) {
            $query = $query->where('name', 'LIKE', "%{$filter}%");
        }
        $query->orderBy('name', $order);
        $dataDb = $query->paginate($totalPage);

        return new PaginatorPresenter($dataDb);
    }

    public function update(Entity $castMember): Entity
    {
        if (!$dataDb = $this->model->find($castMember->id()))
            throw new NotFoundException("Cast Member {$castMember->id()} Not Found");

        $dataDb->update([
            'name' => $castMember->name,
            'type' => $castMember->type->value,
        ]);

        $dataDb->refresh();

        return $this->convertToEntity($dataDb);
    }

    public function delete(string $castMemberId): bool
    {
        if (!$dataDb = $this->model->find($castMemberId))
            throw new NotFoundException("Cast Member {$castMemberId} Not Found");

        return $dataDb->delete();
    }

    private function convertToEntity(Model $model): Entity
    {
        return new Entity(
            name: $model->name,
            type: CastMemberType::from($model->type),
            id: new ValueObjectUuid($model->id),
            createdAt: $model->created_at
        );
    }

    public function getIdsListIds(array $castMembersIds = []): array
    {
        return $this->model
            ->whereIn('id', $castMembersIds)
            ->pluck('id')
            ->toArray();
    }
}
