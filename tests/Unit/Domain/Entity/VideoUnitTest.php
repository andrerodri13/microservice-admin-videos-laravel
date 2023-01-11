<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Video;
use Core\Domain\Enum\MediaStatus;
use Core\Domain\Enum\Rating;
use Core\Domain\ValueObject\Image;
use Core\Domain\ValueObject\Media;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class VideoUnitTest extends TestCase
{

    public function testAttributes()
    {
        $uuid = (string)RamseyUuid::uuid4();

        $entity = new Video(
            title: 'New Title',
            description: 'description',
            yearLaunched: 2020,
            duration: 120,
            opened: true,
            rating: Rating::RATE12,
            id: new ValueObjectUuid($uuid),
            published: true,
            createdAt: new DateTime(date('Y-m-d H:i:s')),
        );

        $this->assertEquals($uuid, $entity->id());
        $this->assertEquals('New Title', $entity->title);
        $this->assertEquals('description', $entity->description);
        $this->assertEquals(2020, $entity->yearLaunched);
        $this->assertEquals(120, $entity->duration);
        $this->assertEquals(true, $entity->opened);
        $this->assertEquals(Rating::RATE12, $entity->rating);
        $this->assertEquals(true, $entity->published);
    }

    public function testIdAndCreatedAt()
    {
        $entity = new Video(
            title: 'New Title',
            description: 'description',
            yearLaunched: 2020,
            duration: 120,
            opened: true,
            rating: Rating::RATE12,
            published: true,
        );

        $this->assertNotEmpty($entity->id());
        $this->assertNotEmpty($entity->createdAt());
    }

    public function testAddCategoryId()
    {
        $categoryId = (string)RamseyUuid::uuid4();

        $entity = new Video(
            title: 'New Title',
            description: 'description',
            yearLaunched: 2020,
            duration: 120,
            opened: true,
            rating: Rating::RATE12,
            published: true,
        );

        $this->assertCount(0, $entity->categoriesId);

        $entity->addCategoryId(
            categoryId: $categoryId,
        );
        $entity->addCategoryId(
            categoryId: $categoryId,
        );

        $this->assertCount(2, $entity->categoriesId);
    }

    public function testRemoveCategoryId()
    {
        $categoryId = (string)RamseyUuid::uuid4();

        $entity = new Video(
            title: 'New Title',
            description: 'description',
            yearLaunched: 2020,
            duration: 120,
            opened: true,
            rating: Rating::RATE12,
            published: true,
        );

        $entity->addCategoryId(
            categoryId: $categoryId,
        );
        $entity->addCategoryId(
            categoryId: 'uuid',
        );

        $this->assertCount(2, $entity->categoriesId);

        $entity->removeCategoryId(
            categoryId: $categoryId
        );

        $this->assertCount(1, $entity->categoriesId);
    }

    public function testAddGenreId()
    {
        $genreId = (string)RamseyUuid::uuid4();

        $entity = new Video(
            title: 'New Title',
            description: 'description',
            yearLaunched: 2020,
            duration: 120,
            opened: true,
            rating: Rating::RATE12,
            published: true,
        );

        $this->assertCount(0, $entity->genresId);

        $entity->addGenreId(
            genreId: $genreId,
        );
        $entity->addGenreId(
            genreId: $genreId,
        );

        $this->assertCount(2, $entity->genresId);
    }

    public function testRemoveGenreId()
    {
        $genreId = (string)RamseyUuid::uuid4();

        $entity = new Video(
            title: 'New Title',
            description: 'description',
            yearLaunched: 2020,
            duration: 120,
            opened: true,
            rating: Rating::RATE12,
            published: true,
        );

        $entity->addGenreId(
            genreId: $genreId,
        );
        $entity->addGenreId(
            genreId: 'uuid',
        );

        $this->assertCount(2, $entity->genresId);

        $entity->removeGenreId(
            genreId: $genreId
        );

        $this->assertCount(1, $entity->genresId);
    }

    public function testAddCastMemberId()
    {
        $castMemberId = (string)RamseyUuid::uuid4();

        $entity = new Video(
            title: 'New Title',
            description: 'description',
            yearLaunched: 2020,
            duration: 120,
            opened: true,
            rating: Rating::RATE12,
            published: true,
        );

        $this->assertCount(0, $entity->castMembersId);

        $entity->addCastMemberId(
            castMemberId: $castMemberId,
        );
        $entity->addCastMemberId(
            castMemberId: $castMemberId,
        );

        $this->assertCount(2, $entity->castMembersId);
    }

    public function testRemoveCastMemberId()
    {
        $castMemberId = (string)RamseyUuid::uuid4();

        $entity = new Video(
            title: 'New Title',
            description: 'description',
            yearLaunched: 2020,
            duration: 120,
            opened: true,
            rating: Rating::RATE12,
            published: true,
        );

        $entity->addCastMemberId(
            castMemberId: $castMemberId,
        );
        $entity->addCastMemberId(
            castMemberId: 'uuid',
        );

        $this->assertCount(2, $entity->castMembersId);

        $entity->removeCastMemberId(
            castMemberId: $castMemberId
        );

        $this->assertCount(1, $entity->castMembersId);
    }

    public function testValueObjectImage()
    {
        $entity = new Video(
            title: 'New Title',
            description: 'description',
            yearLaunched: 2020,
            duration: 120,
            opened: true,
            rating: Rating::RATE12,
            published: true,
            thumbFile: new Image(
                path: 'test-path/image-filmex.png'
            ),
        );

        $this->assertNotNull($entity->thumbFile());
        $this->assertInstanceOf(Image::class, $entity->thumbFile());
        $this->assertEquals('test-path/image-filmex.png', $entity->thumbFile()->path());

    }

    public function testValueObjectImageToThumbHalf()
    {
        $entity = new Video(
            title: 'New Title',
            description: 'description',
            yearLaunched: 2020,
            duration: 120,
            opened: true,
            rating: Rating::RATE12,
            published: true,
            thumbHalf: new Image(
                path: 'test-path/image-filmex.png'
            ),
        );

        $this->assertNotNull($entity->thumbHalf());
        $this->assertInstanceOf(Image::class, $entity->thumbHalf());
        $this->assertEquals('test-path/image-filmex.png', $entity->thumbHalf()->path());
    }

    public function testValueObjectMedia()
    {
        $trailerFile = new Media(
            filePath: 'path/trailer.mp4',
            mediaStatus: MediaStatus::PENDING,
            encodedPath: 'path/encoded.extension',
        );

        $entity = new Video(
            title: 'New Title',
            description: 'description',
            yearLaunched: 2020,
            duration: 120,
            opened: true,
            rating: Rating::RATE12,
            published: true,
            trailerFile: $trailerFile,
        );

        $this->assertNotNull($entity->trailerFile());
        $this->assertInstanceOf(Media::class, $entity->trailerFile());
        $this->assertEquals('path/trailer.mp4', $entity->trailerFile()->filePath);
    }

    public function testValueObjectMediaVideoFile()
    {
        $videoFile = new Media(
            filePath: 'path/video.mp4',
            mediaStatus: MediaStatus::COMPLETE,
        );

        $entity = new Video(
            title: 'New Title',
            description: 'description',
            yearLaunched: 2020,
            duration: 120,
            opened: true,
            rating: Rating::RATE12,
            published: true,
            videoFile: $videoFile,
        );

        $this->assertNotNull($entity->videoFile());
        $this->assertInstanceOf(Media::class, $entity->videoFile());
        $this->assertEquals('path/video.mp4', $entity->videoFile()->filePath);
    }

    public function testValueObjectImageToBannerFile()
    {
        $entity = new Video(
            title: 'New Title',
            description: 'description',
            yearLaunched: 2020,
            duration: 120,
            opened: true,
            rating: Rating::RATE12,
            published: true,
            bannerFile: new Image(
                path: 'test-path/banner.png'
            ),
        );

        $this->assertNotNull($entity->bannerFile());
        $this->assertInstanceOf(Image::class, $entity->bannerFile());
        $this->assertEquals('test-path/banner.png', $entity->bannerFile()->path());
    }


}
