<?php

namespace Bakgat\Notos;


use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class NotosServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /*$this->loadViewsFrom(__DIR__ . '/views', 'notos');
        $this->publishes([
            __DIR__ . '/views' => base_path('resources/views'),
        ]);

        $this->extendAuthManager();*/

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //include __DIR__ . '/Http/routes.php';
        App::register(\Atrauzzi\LaravelDoctrine\ServiceProvider::class);

        $this->mergeConfigFrom(
            __DIR__ . '/../config/doctrine.php', 'doctrine'
        );

        /*
         |----------------------------------------------------------------------------------------------
         | Repositories
         |----------------------------------------------------------------------------------------------
         |
         |
         */

        /*$this->bindKindRepository();

        $this->bindUserRepository();
        $this->bindOrganizationRepository();

        $this->bindPartyRelationRepository();
*/
        /*
         |----------------------------------------------------------------------------------------------
         | Services
         |----------------------------------------------------------------------------------------------
         |
         |
         */
        /*$this->bindUserService();
        $this->bindOrganizationService();*/
    }


    /* ***************************************************
     * Private methods
     * **************************************************/
  /*  private function bindKindRepository()
    {
        $this->app->bind(KindRepository::class, function ($app) {
            return new KindCacheRepository(
                $app->make(EntityManager::class)
            );
        });
    }

    private function bindUserRepository()
    {
        $this->app->bind(UserRepository::class, function ($app) {
            return new UserDoctrineORMRepository(
                $app->make(EntityManager::class)
            );
        });
    }

    private function bindOrganizationRepository()
    {
        $this->app->bind(OrganizationRepository::class, function ($app) {
            return new OrganizationDoctrineORMRepository(
                $app->make(EntityManager::class)
            );
        });
    }

    private function bindPartyRelationRepository()
    {
        $this->app->bind(PartyRelationRepository::class, function ($app) {
            return new PartyRelationDoctrineORMRepository(
                $app->make(EntityManager::class)
            );
        });
    }

    private function bindUserService()
    {
        $this->app->bind(UserSerivce::class, function ($app) {
            return new UserService(
                $app->make(UserRepository::class),
                $app->make(OrganizationRepository::class),
                $app->make(KindRepository::class),
                $app->make(PartyRelationRepository::class),
                $app->make(Hasher::class)
            );
        });
    }

    private function bindOrganizationService()
    {
        $this->app->bind(OrganizationService::class, function ($app) {
            return new OrganizationService(
                $app->make(OrganizationRepository::class),
                $app->make(KindRepository::class),
                $app->make(PartyRelationRepository::class)
            );
        });
    }

    private function extendAuthManager()
    {
        $this->app[AuthManager::class]->extend('notos', function ($app) {
            return new NotosUserProvider(
                $app['Illuminate\Contracts\Hashing\Hasher'],
                $app[IlluminateRegistry::class],
                config('auth.model')
            );
        });
    }*/
}