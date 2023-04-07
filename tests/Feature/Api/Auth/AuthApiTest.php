<?php

namespace Tests\Feature\Api\Auth;

use Tests\TestCase;

class AuthApiTest extends TestCase
{
    public function test_autentication_api_category()
    {
        $this->getJson('/api/categories')->assertStatus(401);
        $this->getJson('/api/categories/id_fake')->assertStatus(401);
        $this->postJson('/api/categories')->assertStatus(401);
        $this->putJson('/api/categories/fakeid')->assertStatus(401);
        $this->deleteJson('/api/categories/fakeid')->assertStatus(401);

    }

    public function test_autentication_api_genres()
    {
        $this->getJson('/api/genres')->assertStatus(401);
        $this->getJson('/api/genres/id_fake')->assertStatus(401);
        $this->postJson('/api/genres')->assertStatus(401);
        $this->putJson('/api/genres/fakeid')->assertStatus(401);
        $this->deleteJson('/api/genres/fakeid')->assertStatus(401);

    }

    public function test_autentication_api_cast_members()
    {
        $this->getJson('/api/cast_members')->assertStatus(401);
        $this->getJson('/api/cast_members/id_fake')->assertStatus(401);
        $this->postJson('/api/cast_members')->assertStatus(401);
        $this->putJson('/api/cast_members/fakeid')->assertStatus(401);
        $this->deleteJson('/api/cast_members/fakeid')->assertStatus(401);

    }

    public function test_autentication_api_videos()
    {
        $this->getJson('/api/videos')->assertStatus(401);
        $this->getJson('/api/videos/id_fake')->assertStatus(401);
        $this->postJson('/api/videos')->assertStatus(401);
        $this->putJson('/api/videos/fakeid')->assertStatus(401);
        $this->deleteJson('/api/videos/fakeid')->assertStatus(401);
    }
}
