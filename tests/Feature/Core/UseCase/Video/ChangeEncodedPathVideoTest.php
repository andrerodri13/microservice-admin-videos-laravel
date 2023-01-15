<?php

namespace Tests\Feature\Core\UseCase\Video;

use App\Models\Video;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\ChangeEncoded\ChangeEncodePathVideo;
use Core\UseCase\Video\ChangeEncoded\DTO\ChangeEncodedVideoInputDTO;
use Tests\TestCase;

class ChangeEncodedPathVideoTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIfUpdatedMediaInDatabase()
    {
        $video = Video::factory()->create();

        $useCase = new ChangeEncodePathVideo(
            $this->app->make(VideoRepositoryInterface::class)
        );

        $input = new ChangeEncodedVideoInputDTO(
            id: $video->id,
            encodedPath: 'path-id/video_encoded.ext',
        );

        $useCase->execute($input);
        $this->assertDatabaseHas('medias_video', [
            'video_id' => $input->id,
            'encoded_path' => $input->encodedPath
        ]);
    }
}
