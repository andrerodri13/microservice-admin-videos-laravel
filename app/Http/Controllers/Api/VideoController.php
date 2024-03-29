<?php

namespace App\Http\Controllers\Api;

use App\Adapters\ApiAdapter;
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
use function MongoDB\BSON\toJSON;

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

        return (new ApiAdapter($response))->toJson();
    }

    public function show(ListVideoUseCase $useCase, $id)
    {
        $response = $useCase->execute(new ListInputVideoDTO($id));
        return ApiAdapter::json($response);
    }

    public function store(CreateVideoUseCase $useCase, StoreVideoRequest $request)
    {
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
            videoFile: getArrayFile($request->file('video_file')),
            trailerFile: getArrayFile($request->file('trailer_file')),
            thumbFile: getArrayFile($request->file('thumb_file')),
            thumbHalf: getArrayFile($request->file('thumb_half_file')),
            bannerFile: getArrayFile($request->file('banner_file')),
        ));

        return ApiAdapter::json($response, Response::HTTP_CREATED);
    }

    public function update(UpdateVideoUseCase $useCase, UpdateVideoRequest $request, $id)
    {
        $response = $useCase->execute(new UpdateInputVideoDTO(
            id: $id,
            title: $request->title,
            description: $request->description,
            categories: $request->categories,
            genres: $request->genres,
            castMembers: $request->cast_members,
            videoFile: getArrayFile($request->file('video_file')),
            trailerFile: getArrayFile($request->file('trailer_file')),
            thumbFile: getArrayFile($request->file('thumb_file')),
            thumbHalf: getArrayFile($request->file('thumb_half_file')),
            bannerFile: getArrayFile($request->file('banner_file')),
        ));

        return ApiAdapter::json($response);

    }

    public function destroy(DeleteVideoUseCase $useCase, $id)
    {
        $useCase->execute(new DeleteInputVideoDTO($id));
        return response()->noContent();
    }

}
