<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Genre;
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\ValueObject\Uuid;
use DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class GenreUnitTest extends TestCase
{

    public function testAttributes()
    {
        $uuid = (string)RamseyUuid::uuid4();
        $date = date('Y-m-d H:i:s');

        $genre = new Genre(
            name: 'New Genre',
            id: new Uuid($uuid),
            isActive: false,
            createdAt: new DateTime($date),
        );

        $this->assertEquals($uuid, $genre->id());
        $this->assertEquals('New Genre', $genre->name);
        $this->assertEquals(false, $genre->isActive);
        $this->assertEquals($date, $genre->createdAt());
    }

    public function testAttributesCreate()
    {
        $genre = new Genre(
            name: 'New Genre',
        );

        $this->assertNotEmpty($genre->id());
        $this->assertEquals('New Genre', $genre->name);
        $this->assertEquals(true, $genre->isActive);
        $this->assertNotEmpty($genre->createdAt());
    }

    public function testDeactivate()
    {
        $genre = new Genre(
            name: 'teste'
        );

        $this->assertTrue($genre->isActive);
        $genre->deactivate();
        $this->assertFalse($genre->isActive);
    }

    public function testActivate()
    {
        $genre = new Genre(
            name: 'teste',
            isActive: false
        );

        $this->assertFalse($genre->isActive);
        $genre->activate();
        $this->assertTrue($genre->isActive);
    }

    public function testUpdate()
    {
        $genre = new Genre(
            name: 'Teste'
        );

        $this->assertEquals('Teste', $genre->name);

        $genre->update(
            name: 'New updated'
        );

        $this->assertEquals('New updated', $genre->name);

    }

    public function testEntityExceptions()
    {
        $this->expectException(EntityValidationException::class);
        new Genre(
            name: 's',
        );
    }

    public function testEntityUpdateException()
    {
        $this->expectException(EntityValidationException::class);

        $uuid = (string)RamseyUuid::uuid4();
        $date = date('Y-m-d H:i:s');

        $genre = new Genre(
            name: 'New Genre',
            id: new Uuid($uuid),
            isActive: false,
            createdAt: new DateTime($date),
        );

        $genre->update(name: 's');
    }

    public function testAddCategoryToGenre()
    {
        $categoryId = (string)RamseyUuid::uuid4();

        $genre = new Genre(
            name: 'New Genre'
        );

        $this->assertIsArray($genre->categoriesId);
        $this->assertCount(0, $genre->categoriesId);

        $genre->addCategory(
            categoryId: $categoryId
        );
        $genre->addCategory(
            categoryId: $categoryId
        );

        $this->assertCount(2, $genre->categoriesId);
    }

    public function testRemoveCategoryToGenre()
    {
        $categoryId = (string)RamseyUuid::uuid4();
        $categoryId2 = (string)RamseyUuid::uuid4();

        $genre = new Genre(
            name: 'New Genre',
            categoriesId: [
                $categoryId,
                $categoryId2
            ]
        );

        $this->assertCount(2, $genre->categoriesId);

        $genre->removeCategory(
            categoryId: $categoryId
        );
        $this->assertCount(1, $genre->categoriesId);
        $this->assertEquals($categoryId2, $genre->categoriesId[1]);
    }
}
