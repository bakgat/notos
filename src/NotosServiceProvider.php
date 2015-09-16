<?php

namespace Bakgat\Notos;


use Atrauzzi\LaravelDoctrine\DoctrineRegistry;
use Bakgat\Notos\Domain\Model\ACL\RoleRepository;
use Bakgat\Notos\Domain\Model\ACL\UserRolesRepository;
use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use Bakgat\Notos\Domain\Model\Identity\UserRepository;
use Bakgat\Notos\Domain\Model\Relations\PartyRelationRepository;
use Bakgat\Notos\Infrastructure\Repositories\ACL\RoleDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\ACL\UserRolesDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\OrganizationDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\PartyRelationDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\UserDoctrineORMRepository;
use Bakgat\Notos\Providers\NotosUserProvider;
use Bakgat\Notos\Tests\Infrastructure\Repositories\ACL\UserRolesDoctrineORMRepositoryTest;
use Doctrine\ORM\EntityManager;
use Illuminate\Auth\AuthManager;
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


        */
        $this->extendAuthManager();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Bootstrap the JMS custom annotations for Object to Json mapping
        \Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
            'JMS\Serializer\Annotation',
            __DIR__ . '/../vendor/jms/serializer/src'
        );

        include __DIR__ . '/Http/routes.php';

        $this->app->register(\Atrauzzi\LaravelDoctrine\ServiceProvider::class);
        //$this->app->register(\Atrauzzi\LaravelSerializer\ServiceProvider::class);

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('EntityManager', \Atrauzzi\LaravelDoctrine\Support\Facades\Doctrine::class);

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

        /*$this->bindKindRepository();*/
        $repos = [
            [UserRepository::class, UserDoctrineORMRepository::class],
            [OrganizationRepository::class, OrganizationDoctrineORMRepository::class],
            [PartyRelationRepository::class, PartyRelationDoctrineORMRepository::class],
            [RoleRepository::class, RoleDoctrineORMRepository::class],
            [UserRolesRepository::class, UserRolesDoctrineORMRepository::class]
        ];
        $this->simpleBindRepositories($repos);
        /*
        $this->bindUserRepository();
        $this->bindOrganizationRepository();

        $this->bindPartyRelationRepository();

        $this->bindRoleRepository();
        $this->bindUserRoleRepository();*/

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
    private function simpleBindRepositories($repos)
    {
        foreach ($repos as $repo) {
            $this->app->bind($repo[0], function ($app) use($repo) {
                return new $repo[1](
                    $app->make(EntityManager::class)
                );
            });
        }

    }

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

    private function bindRoleRepository()
    {
        $this->app->bind(RoleRepository::class, function ($app) {
            return new RoleDoctrineORMRepository(
                $app->make(EntityManager::class)
            );
        });
    }

    /*
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
*/


    private function extendAuthManager()
    {
        $this->app[AuthManager::class]->extend('notos', function ($app) {
            return new NotosUserProvider(
                $app['Illuminate\Contracts\Hashing\Hasher'],
                $app[EntityManager::class],
                config('auth.model')
            );
        });
    }
}
