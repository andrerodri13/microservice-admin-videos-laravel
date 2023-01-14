<?php

namespace App\Providers;

use App\Events\{VideoEvent};
use App\Services\Storage\{FileStorage};
use App\Repositories\Eloquent\{
    CastMemberEloquentRepository,
    CategoryEloquentRepository,
    GenreEloquentRepository,
    VideoEloquentRepository
};
use Core\Domain\Repository\{
    CastMemberRepositoryInterface,
    CategoryRepositoryInterface,
    GenreRepositoryInterface,
    VideoRepositoryInterface
};
use App\Repositories\Transaction\DBTransaction;

use Core\UseCase\Interfaces\{EventManagerInterface, FileStorageInterface, TransactionInterface};
use Illuminate\Support\ServiceProvider;

class CleanArchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindingRepositories();

        $this->app->singleton(FileStorageInterface::class, FileStorage::class);
        $this->app->singleton(EventManagerInterface::class, VideoEvent::class);
        /**
         * DB Transaction
         */
        $this->app->bind(
            TransactionInterface::class, DBTransaction::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    private function bindingRepositories()
    {
        $this->app->singleton(CategoryRepositoryInterface::class, CategoryEloquentRepository::class);
        $this->app->singleton(GenreRepositoryInterface::class, GenreEloquentRepository::class);
        $this->app->singleton(CastMemberRepositoryInterface::class, CastMemberEloquentRepository::class);
        $this->app->singleton(VideoRepositoryInterface::class, VideoEloquentRepository::class);

    }
}
