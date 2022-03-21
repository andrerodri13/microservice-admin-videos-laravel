<?php


namespace Tests\Unit\UseCase\Category;


use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\DTO\Category\CategoryInputDto;
use Core\DTO\Category\CategoryOutputDto;
use Core\UseCase\Category\ListCategoryUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class ListCategoryUseCaseUnitTest extends TestCase
{

    public function testGetByid()
    {
        $id = (string)Uuid::uuid4()->toString();

        //Mock entidade category
        $this->mockEntity = Mockery::mock(Category::class, [
            $id,
            'teste category'
        ]);
        $this->mockEntity->shouldReceive('id')->andReturn($id);
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        //Mock utilizando o stdClass e fazendo ele implementar a intergace CategoryRepositoryInterface
        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        //Mock implementa o método insert e retorna o objeto do mock da entity categoria
        $this->mockRepo->shouldReceive('findById')
            ->with($id)
            ->andReturn($this->mockEntity);

        //Mock do DTO category Input
        $this->mockinputDto = Mockery::mock(CategoryInputDto::class, [
            $id,
        ]);

        $useCase = new ListCategoryUseCase($this->mockRepo);
        $response = $useCase->execute($this->mockinputDto);

        $this->assertInstanceOf(CategoryOutputDto::class, $response);
        $this->assertEquals('teste category', $response->name);
        $this->assertEquals($id, $response->id);

        /**
         * spies - Verificar se quando roda o execute esta chamando o metodo correto
         */
        $this->spy = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('findById')->with($id)->andReturn($this->mockEntity);
        $useCase = new ListCategoryUseCase($this->spy);
        $response = $useCase->execute($this->mockinputDto);
        $this->spy->shouldHaveReceived('findById');

    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }




}