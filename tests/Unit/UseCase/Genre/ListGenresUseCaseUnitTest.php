<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\DTO\Genre\ListGenre\ListGenresInputDto;
use Core\DTO\Genre\ListGenre\ListGenresOutputDto;
use Core\UseCase\Genre\ListGenresUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class ListGenresUseCaseUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_usecase()
    {
        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('paginate')->once()->andReturn($this->mockPagination());
        $mockDtoInput = Mockery::mock(ListGenresInputDto::class, [
            'teste', 'desc', 1, 15
        ]);
        $useCase = new ListGenresUseCase($mockRepository);
        $response = $useCase->execute($mockDtoInput);

        $this->assertInstanceOf(ListGenresOutputDto::class, $response);

        Mockery::close();

        /**
         * Spies
         * Para monitorar parametros passados.
        */
        //arrange
        $spy = Mockery::spy(stdClass::class, GenreRepositoryInterface::class);
        $spy->shouldReceive('paginate')->andReturn($this->mockPagination());
        $sut = new ListGenresUseCase($spy);

        //action
        $sut->execute($mockDtoInput);

        //assert
        $spy->shouldHaveReceived()->paginate(
            'teste', 'desc', 1, 15
        );

    }


    protected function mockPagination(array $items = [])
    {
        $this->mockPagination = Mockery::mock(stdClass::class, PaginationInterface::class);
        $this->mockPagination->shouldReceive('items')->andReturn($items);
        $this->mockPagination->shouldReceive('total')->andReturn(0);
        $this->mockPagination->shouldReceive('currentPage')->andReturn(0);
        $this->mockPagination->shouldReceive('firstPage')->andReturn(0);
        $this->mockPagination->shouldReceive('lastPage')->andReturn(0);
        $this->mockPagination->shouldReceive('perPage')->andReturn(0);
        $this->mockPagination->shouldReceive('to')->andReturn(0);
        $this->mockPagination->shouldReceive('from')->andReturn(0);

        return $this->mockPagination;
    }
}
