<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Http\Resources\GenreResource;
use Core\DTO\Genre\CreateGenre\GenreCreateInputDto;
use Core\DTO\Genre\GenreInputDto;
use Core\DTO\Genre\UpdateGenre\GenreUpdateInputDto;
use Illuminate\Http\Response;
use Core\DTO\Genre\ListGenre\{ListGenresInputDto};
use Core\UseCase\Genre\{CreateGenreUseCase,
    DeleteGenreUseCase,
    ListGenresUseCase,
    ListGenreUseCase,
    UpdateGenreUseCase
};
use Illuminate\Http\Request;

class GenreController extends Controller
{

    public function index(Request $request, ListGenresUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new ListGenresInputDto(
                filter: $request->get('filter', ''),
                order: $request->get('order', 'DESC'),
                page: (int)$request->get('page', 1),
                totalPage: (int)$request->get('total_page', 15))
        );

        return GenreResource::collection(collect($response->items))
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


    public function store(StoreGenreRequest $request, CreateGenreUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new GenreCreateInputDto(
                name: $request->name,
                categoriesId: $request->categories_ids,
                isActive: (bool)$request->is_active
            )
        );

        return (new GenreResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ListGenreUseCase $useCase, $id)
    {
        $response = $useCase->execute(
            input: new GenreInputDto(
                id: $id
            )
        );

        return new GenreResource($response);
    }


    public function update(UpdateGenreRequest $request, UpdateGenreUseCase $useCase, $id)
    {
        $response = $useCase->execute(
            input: new GenreUpdateInputDto(
                id: $id,
                name: $request->name,
                categoriesId: $request->categories_ids
            )
        );

        return new GenreResource($response);
    }


    public function destroy(DeleteGenreUseCase $useCase, $id)
    {
        $useCase->execute(new GenreInputDto($id));

        return response()->noContent();
    }
}
