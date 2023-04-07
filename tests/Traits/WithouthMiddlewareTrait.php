<?php

namespace Tests\Traits;

use App\Http\Middleware\Authenticate;
use Illuminate\Auth\Middleware\Authorize;

trait WithouthMiddlewareTrait
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware([
            Authenticate::class,
            Authorize::class,
        ]);
    }
}
