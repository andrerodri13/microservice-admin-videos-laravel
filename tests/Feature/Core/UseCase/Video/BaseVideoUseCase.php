<?php

namespace Tests\Feature\Core\UseCase\Video;

use App\Models\{CastMember, Category, Genre};
use Core\Domain\Repository\{CastMemberRepositoryInterface,
    CategoryRepositoryInterface,
    GenreRepositoryInterface,
    VideoRepositoryInterface
};
use Core\UseCase\Interfaces\{TransactionInterface};
use Exception;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Tests\Stubs\UploadFilesStub;
use Tests\Stubs\VideoEventStub;
use Tests\TestCase;
use Throwable;

abstract class BaseVideoUseCase extends TestCase
{

    abstract function useCase(): string;

    abstract function inputDTO(
        array  $categories = [],
        array  $genres = [],
        array  $castMembers = [],
        ?array $videoFile = null,
        ?array $trailerFile = null,
        ?array $thumbFile = null,
        ?array $thumbHalf = null,
        ?array $bannerFile = null
    ): object;

    /**
     * @dataProvider provider
     */
    public function test_action(
        int  $categories,
        int  $genres,
        int  $castMembers,
        bool $withMediaVideo = false,
        bool $withTrailer = false,
        bool $withThumb = false,
        bool $withThumbHalf = false,
        bool $withBanner = false,
    )
    {
        $stu = $this->makeSut();

        $categoriesIds = Category::factory()->count($categories)->create()->pluck('id')->toArray();
        $genresIds = Genre::factory()->count($genres)->create()->pluck('id')->toArray();
        $castMembersIds = CastMember::factory()->count($castMembers)->create()->pluck('id')->toArray();

        $fakeFile = UploadedFile::fake()->create('video.mp4', 1, 'video/mp4');
        $file = [
            'tmp_name' => $fakeFile->getPathname(),
            'name' => $fakeFile->getFilename(),
            'type' => $fakeFile->getMimeType(),
            'error' => $fakeFile->getError(),
        ];

        $input = $this->inputDTO(
            categories: $categoriesIds,
            genres: $genresIds,
            castMembers: $castMembersIds,
            videoFile: $withMediaVideo ? $file : null,
            trailerFile: $withTrailer ? $file : null,
            thumbFile: $withThumb ? $file : null,
            thumbHalf: $withThumbHalf ? $file : null,
            bannerFile: $withBanner ? $file : null,
        );

        $response = $stu->execute($input);

        $this->assertEquals($input->title, $response->title);
        $this->assertEquals($input->description, $response->description);
//        $this->assertEquals($input->yearLaunched, $response->yearLaunched);
//        $this->assertEquals($input->duration, $response->duration);
//        $this->assertEquals($input->opened, $response->opened);
//        $this->assertEquals($input->rating, $response->rating);

        $this->assertCount($categories, $response->categories);
        $this->assertEqualsCanonicalizing($input->categories, $response->categories);
        $this->assertCount($genres, $response->genres);
        $this->assertEqualsCanonicalizing($input->genres, $response->genres);
        $this->assertCount($castMembers, $response->castMembers);
        $this->assertEqualsCanonicalizing($input->castMembers, $response->castMembers);

        $this->assertTrue($withMediaVideo ? $response->videoFile !== null : $response->videoFile === null);
        $this->assertTrue($withTrailer ? $response->trailerFile !== null : $response->trailerFile === null);
        $this->assertTrue($withThumb ? $response->thumbFile !== null : $response->thumbFile === null);
        $this->assertTrue($withThumbHalf ? $response->thumbHalf !== null : $response->thumbHalf === null);
        $this->assertTrue($withBanner ? $response->bannerFile !== null : $response->bannerFile === null);

    }

    protected function provider(): array
    {
        return [
            'Test With All IDs And Media Video' => [
                'categories' => 3,
                'genres' => 3,
                'castMembers' => 3,
                'withMediaVideo' => true,
                'withTrailer' => false,
                'withThumb' => false,
                'withThumbHalf' => false,
                'withBanner' => false,
            ],
            'Test With categories and genres IDs and without files' => [
                'categories' => 3,
                'genres' => 3,
                'castMembers' => 0,
            ],
            'Test With All IDs and All Medias' => [
                'categories' => 2,
                'genres' => 2,
                'castMembers' => 2,
                'withMediaVideo' => true,
                'withTrailer' => true,
                'withThumb' => true,
                'withThumbHalf' => true,
                'withBanner' => true,
            ],
            'Test Without IDs and All Medias' => [
                'categories' => 0,
                'genres' => 0,
                'castMembers' => 0,
                'withMediaVideo' => true,
                'withTrailer' => true,
                'withThumb' => true,
                'withThumbHalf' => true,
                'withBanner' => true,
            ],
        ];
    }

    protected function makeSut()
    {
        return new ($this->useCase())(
            $this->app->make(VideoRepositoryInterface::class),
            $this->app->make(TransactionInterface::class),
//            $this->app->make(FileStorageInterface::class),
            new UploadFilesStub(),
//            $this->app->make(VideoEventManagerInterface::class),
            new VideoEventStub(),
            $this->app->make(CategoryRepositoryInterface::class),
            $this->app->make(GenreRepositoryInterface::class),
            $this->app->make(CastMemberRepositoryInterface::class),
        );
    }

    public function testTransactionException()
    {

        Event::listen(TransactionBeginning::class, function () {
            throw new Exception('begin transaction');
        });

        try {
            $sut = $this->makeSut();

            $sut->execute($this->inputDTO());
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertDatabaseCount('videos', 0);
        }
    }

    public function testUploadFilesException()
    {
        Event::listen(UploadFilesStub::class, function () {
            throw new Exception('upload file');
        });

        try {
            $sut = $this->makesut();

            $input = $this->inputdto(
                trailerFile: [
                    'name' => 'video.mp4',
                    'type' => 'video/mp4',
                    'tmp_name' => '/tmp/video.mp4',
                    'error' => 0,
                ]
            );

            $sut->execute($input);
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertdatabasecount('videos', 0);
        }
    }

    public function testEventException()
    {
        Event::listen(VideoEventStub::class, function () {
            throw new Exception('event');
        });

        try {
            $sut = $this->makesut();
            $sut->execute($this->inputdto(
                videoFile: [
                    'name' => 'video.mp4',
                    'type' => 'video/mp4',
                    'tmp_name' => '/tmp/video.mp4',
                    'error' => 0,
                ]
            ));
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertdatabasecount('videos', 0);
        }
    }
}
