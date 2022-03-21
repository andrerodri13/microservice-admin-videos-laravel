<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\DTO\Category\CreateCategory\CategotyCreateInputDto;
use Core\DTO\Category\CreateCategory\CategotyCreateOutputDto;
use Core\UseCase\Category\CreateCategoryUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class CreateCategoryUseCaseUnitTest extends TestCase
{
    public function testCreateNewCategory()
    {
        $uuid = (string) Uuid::uuid4()->toString();
        $categoryName = 'name cat';

        //Mock entidade category
        $this->mockEntity = Mockery::mock(Category::class, [
            $uuid,
            $categoryName
        ]);

        //Mock Chama o metodo id() e retorn um uuid;
        $this->mockEntity->shouldReceive('id')->andReturn($uuid);
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        //Mock utilizando o stdClass e fazendo ele implementar a intergace CategoryRepositoryInterface
        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        //Mock implementa o método insert e retorna o objeto do mock da entity categoria
        $this->mockRepo->shouldReceive('insert')->andReturn($this->mockEntity);

        //Mock do DTO category Input
        $this->mockinputDto = Mockery::mock(CategotyCreateInputDto::class, [
            $categoryName
        ]);

        //faz a chamada do UseCase de criar a categoria passando o mock do repositorio
        $useCase = new CreateCategoryUseCase($this->mockRepo);
        //Executa a operação create passano o mock do input DTO e recebe um objeto do output DTO
        $responseUseCase = $useCase->execute($this->mockinputDto);

        $this->assertInstanceOf(CategotyCreateOutputDto::class, $responseUseCase);
        $this->assertEquals($categoryName, $responseUseCase->name);
        $this->assertEquals('', $responseUseCase->description);

        /**
         * spies - Verificar se quando roda o execute esta chamando o metodo correto
         */
        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('insert')->andReturn($this->mockEntity);

        $useCase = new CreateCategoryUseCase($this->spy);
        $responseUseCase = $useCase->execute($this->mockinputDto);
        $this->spy->shouldHaveReceived('insert');

        Mockery::close();
    }
}