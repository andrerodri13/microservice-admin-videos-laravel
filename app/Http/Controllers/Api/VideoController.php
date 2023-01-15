<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Http\Resources\VideoResource;
use Core\Domain\Enum\Rating;
use Core\UseCase\Video\Create\CreateVideoUseCase;
use Core\UseCase\Video\Create\DTO\CreateInputVideoDTO;
use Core\UseCase\Video\Delete\DeleteVideoUseCase;
use Core\UseCase\Video\Delete\DTO\DeleteInputVideoDTO;
use Core\UseCase\Video\List\DTO\ListInputVideoDTO;
use Core\UseCase\Video\List\ListVideoUseCase;
use Core\UseCase\Video\Update\DTO\UpdateInputVideoDTO;
use Core\UseCase\Video\Update\UpdateVideoUseCase;
use Core\UseCase\Video\Paginate\{ListVideosUseCase};
use Core\UseCase\Video\Paginate\DTO\{PaginateInputVideoDTO};
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VideoController extends Controller
{

    public function index(Request $request, ListVideosUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new PaginateInputVideoDTO(
                filter: $request->filter ?? '',
                order: $request->get('order', 'DESC'),
                page: (int)$request->get('page', 1),
                totalPerPage: (int)$request->get('per_page', 15))
        );


        return VideoResource::collection(collect($response->items))
            ->additional([
                'meta' => [
                    'total' => $response->total,
                    'current_page' => $response->current_page,
                    'last_page' => $response->last_page,
                    'first_page' => $response->first_page,
                    'per_page' => $response->per_page,
                    'to' => $response->to,
                    'from' => $response->from,
                ]
            ]);
    }

    public function show(ListVideoUseCase $useCase, $id)
    {
        $response = $useCase->execute(new ListInputVideoDTO($id));
        return new VideoResource($response);
    }

    public function store(CreateVideoUseCase $useCase, StoreVideoRequest $request)
    {
        if ($file = $request->file('video_file')) {
            $videoFile = [
                'tmp_name' => $file->getPathname(),
                'name' => $file->getFilename(),
                'type' => $file->getMimeType(),
                'error' => $file->getError(),
                'size' => $file->getSize(),
            ];
        }

        if ($file = $request->file('trailer_file')) {
            $trailerFile = [
                'tmp_name' => $file->getPathname(),
                'name' => $file->getFilename(),
                'type' => $file->getMimeType(),
                'error' => $file->getError(),
                'size' => $file->getSize(),
            ];
        }

        if ($file = $request->file('banner_file')) {
            $bannerFile = [
                'tmp_name' => $file->getPathname(),
                'name' => $file->getFilename(),
                'type' => $file->getMimeType(),
                'error' => $file->getError(),
                'size' => $file->getSize(),
            ];
        }
        if ($file = $request->file('thumb_file')) {
            $thumbFile = [
                'tmp_name' => $file->getPathname(),
                'name' => $file->getFilename(),
                'type' => $file->getMimeType(),
                'error' => $file->getError(),
                'size' => $file->getSize(),
            ];
        }

        if ($file = $request->file('thumb_half_file')) {
            $thumbHalfFile = [
                'tmp_name' => $file->getPathname(),
                'name' => $file->getFilename(),
                'type' => $file->getMimeType(),
                'error' => $file->getError(),
                'size' => $file->getSize(),
            ];
        }


        $response = $useCase->execute(new CreateInputVideoDTO(
            title: $request->title,
            description: $request->description,
            yearLaunched: $request->year_launched,
            duration: $request->duration,
            opened: true,
            rating: Rating::from($request->rating),
            categories: $request->categories,
            genres: $request->genres,
            castMembers: $request->cast_members,
            videoFile: $videoFile ?? null,
            trailerFile: $trailerFile ?? null,
            thumbFile: $thumbFile ?? null,
            thumbHalf: $thumbHalfFile ?? null,
            bannerFile: $bannerFile ?? null,
        ));

        return (new VideoResource($response))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateVideoUseCase $useCase, UpdateVideoRequest $request, $id)
    {
        if ($file = $request->file('video_file')) {
            $videoFile = [
                'tmp_name' => $file->getPathname(),
                'name' => $file->getFilename(),
                'type' => $file->getMimeType(),
                'error' => $file->getError(),
                'size' => $file->getSize(),
            ];
        }

        if ($file = $request->file('trailer_file')) {
            $trailerFile = [
                'tmp_name' => $file->getPathname(),
                'name' => $file->getFilename(),
                'type' => $file->getMimeType(),
                'error' => $file->getError(),
                'size' => $file->getSize(),
            ];
        }

        if ($file = $request->file('banner_file')) {
            $bannerFile = [
                'tmp_name' => $file->getPathname(),
                'name' => $file->getFilename(),
                'type' => $file->getMimeType(),
                'error' => $file->getError(),
                'size' => $file->getSize(),
            ];
        }
        if ($file = $request->file('thumb_file')) {
            $thumbFile = [
                'tmp_name' => $file->getPathname(),
                'name' => $file->getFilename(),
                'type' => $file->getMimeType(),
                'error' => $file->getError(),
                'size' => $file->getSize(),
            ];
        }

        if ($file = $request->file('thumb_half_file')) {
            $thumbHalfFile = [
                'tmp_name' => $file->getPathname(),
                'name' => $file->getFilename(),
                'type' => $file->getMimeType(),
                'error' => $file->getError(),
                'size' => $file->getSize(),
            ];
        }


        $response = $useCase->execute(new UpdateInputVideoDTO(
            id: $id,
            title: $request->title,
            description: $request->description,
            categories: $request->categories,
            genres: $request->genres,
            castMembers: $request->cast_members,
            videoFile: $videoFile ?? null,
            trailerFile: $trailerFile ?? null,
            thumbFile: $thumbFile ?? null,
            thumbHalf: $thumbHalfFile ?? null,
            bannerFile: $bannerFile ?? null,
        ));

        return new VideoResource($response);
    }

    public function destroy(DeleteVideoUseCase $useCase, $id)
    {
        $useCase->execute(new DeleteInputVideoDTO($id));
        return response()->noContent();
    }

}
