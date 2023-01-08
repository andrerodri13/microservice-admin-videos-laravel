<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Entity\CastMember as EntityCastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\DTO\CastMember\CreateCastMember\CastMemberCreateInputDto;
use Core\DTO\CastMember\CreateCastMember\CastMemberCreateOutputDto;
use Core\UseCase\CastMember\CreateCastMemberUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class CreateCastMemberUserCaseUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create()
    {
        //arrange
        $mockEntity = Mockery::mock( EntityCastMember::class, ['name', CastMemberType::ACTOR] );
        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepository->shouldReceive('id');
        $mockRepository->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));
        $mockRepository->shouldReceive('insert')->once()->andReturn($mockEntity);

        $useCase = new CreateCastMemberUseCase($mockRepository);

        $mockDto = Mockery::mock(CastMemberCreateInputDto::class, [
            'name', 1
        ]);

        //action
        $response = $useCase->execute($mockDto);

        //assert
        $this->assertInstanceOf(CastMemberCreateOutputDto::class, $response);
        $this->assertNotEmpty($response->id);
        $this->assertEquals('name', $response->name);
        $this->assertEquals(1, $response->type);
        $this->assertNotEmpty($response->createdAt);
        Mockery::close();
    }
}
