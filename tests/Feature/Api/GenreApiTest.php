<?php

namespace Tests\Feature\Api;

use App\Models\Category as CategoryModel;
use App\Models\Genre as GenreModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class GenreApiTest extends TestCase
{

    protected $endpoint = '/api/genres';

    public function test_list_all_empty()
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
    }

    public function test_list_all()
    {
        GenreModel::factory()->count(20)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonStructure([
            'data' => [],
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page',
                'to',
                'from',
            ]
        ]);
    }

    public function test_store()
    {
        $categories = CategoryModel::factory()->count(10)->create();

        $response = $this->postJson($this->endpoint, [
            'name' => 'New genre',
            'is_active' => true,
            'categories_ids' => $categories->pluck('id')->toArray()
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_active'
            ]
        ]);
    }

    public function test_validation_store()
    {
        $categories = CategoryModel::factory()->count(2)->create();

        $payload = [
            'name' => '',
            'is_active' => true,
            'categories_ids' => $categories->pluck('id')->toArray()
        ];
        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name'
            ]
        ]);
    }

    public function test_show_not_found()
    {
        $response = $this->getJson("{$this->endpoint}/fake_id");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_show()
    {
        $genre = GenreModel::factory()->create();

        $response = $this->getJson("{$this->endpoint}/{$genre->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_active'
            ]
        ]);
    }

    public function test_update_not_found()
    {
        $categories = CategoryModel::factory()->count(10)->create();

        $response = $this->putJson("{$this->endpoint}/fake_id", [
            'name' => 'New Name',
            'categories_ids' => $categories->pluck('id')->toArray()
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_validations_update()
    {
        $response = $this->putJson("{$this->endpoint}/fake_value", [
            'name' => 'New Name to Update',
            'categories_ids' => []
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'categories_ids'
            ]
        ]);

    }

    public function test_update()
    {
        $genre = GenreModel::factory()->create();
        $categories = CategoryModel::factory()->count(10)->create();

        $response = $this->putJson("{$this->endpoint}/{$genre->id}", [
            'name' => 'New Name to Update',
            'categories_ids' => $categories->pluck('id')->toArray()
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_active'
            ]
        ]);
    }

    public function test_delete_not_found()
    {
        $response = $this->deleteJson("{$this->endpoint}/fake_id");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_delete()
    {
        $genre = GenreModel::factory()->create();

        $response = $this->deleteJson("{$this->endpoint}/{$genre->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
