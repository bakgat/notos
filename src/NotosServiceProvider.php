<?php

namespace Bakgat\Notos;


use Atrauzzi\LaravelDoctrine\DoctrineRegistry;
use Bakgat\Notos\Domain\Model\ACL\RoleRepository;
use Bakgat\Notos\Domain\Model\ACL\UserRolesRepository;
use Bakgat\Notos\Domain\Model\Curricula\CourseRepository;
use Bakgat\Notos\Domain\Model\Curricula\CurriculumRepository;
use Bakgat\Notos\Domain\Model\Descriptive\TagRepository;
use Bakgat\Notos\Domain\Model\Event\CalendarRepository;
use Bakgat\Notos\Domain\Model\Identity\GroupRepository;
use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use Bakgat\Notos\Domain\Model\Identity\PartyRepository;
use Bakgat\Notos\Domain\Model\Identity\UserRepository;
use Bakgat\Notos\Domain\Model\KindRepository;
use Bakgat\Notos\Domain\Model\Location\BlogRepository;
use Bakgat\Notos\Domain\Model\Location\WebsitesRepository;
use Bakgat\Notos\Domain\Model\Relations\PartyRelationRepository;
use Bakgat\Notos\Domain\Model\Resource\AssetRepository;
use Bakgat\Notos\Domain\Model\Resource\BookRepository;
use Bakgat\Notos\Exceptions\Handler;
use Bakgat\Notos\Exceptions\NotosExceptionHandler;
use Bakgat\Notos\Infrastructure\Repositories\Curriculum\CurriculumDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Descriptive\TagDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Event\CalendarDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Identity\GroupDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Identity\KindCacheRepository;
use Bakgat\Notos\Infrastructure\Repositories\Identity\PartyDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Location\BlogDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Location\WebsitesDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\ACL\RoleDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\ACL\UserRolesDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Curriculum\CourseDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Identity\OrganizationDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Identity\PartyRelationDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Resource\AssetDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Resource\BookDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Identity\UserDoctrineORMRepository;
use Bakgat\Notos\Providers\NotosUserProvider;
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

        $this->publishes([__DIR__ . '../config/doctrine.php' => config_path('doctrine.php')], 'config');
        $this->publishes([__DIR__ . '../config/errors.php' => config_path('errors.php')], 'config');

        $this->extendAuthManager();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();

        $this->registerRoutes();
        $this->registerJSMAnnotations();

        $this->registerLaravelDoctrine($loader);
        $this->registerIntervention($loader);

        $this->mergeConfigs();

        $repos = $this->getRepositories();
        $this->simpleBindRepositories($repos);

        //TODO register exception handler from this package
        //TODO now it is catched in Handler => app/Exceptions
        //$this->registerExceptionHandler();
    }

    private function simpleBindRepositories($repos)
    {
        foreach ($repos as $repo) {
            $this->app->bind($repo[0], function ($app) use ($repo) {
                return new $repo[1](
                    $app->make(EntityManager::class)
                );
            });
        }

    }

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

    private function registerRoutes()
    {
        include __DIR__ . '/Http/routes.php';
    }

    private function registerJSMAnnotations()
    {
        // Bootstrap the JMS custom annotations for Object to Json mapping
        \Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
            'JMS\Serializer\Annotation',
            app_path() . '/../vendor/jms/serializer/src'
        );
    }

    /**
     * @param $loader
     */
    private function registerLaravelDoctrine($loader)
    {
        $this->app->register(\Atrauzzi\LaravelDoctrine\ServiceProvider::class);
        $loader->alias('EntityManager', \Atrauzzi\LaravelDoctrine\Support\Facades\Doctrine::class);
    }

    /**
     * @param $loader
     */
    private function registerIntervention($loader)
    {
        $this->app->register(\Intervention\Image\ImageServiceProvider::class);

        $loader->alias('Image', \Intervention\Image\Facades\Image::class);
    }

    private function mergeConfigs()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/doctrine.php', 'doctrine'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../config/errors.php', 'errors'
        );
    }

    /**
     * @return array
     */
    private function getRepositories()
    {
        $repos = [
            [PartyRepository::class, PartyDoctrineORMRepository::class],
            [UserRepository::class, UserDoctrineORMRepository::class],
            [OrganizationRepository::class, OrganizationDoctrineORMRepository::class],
            [PartyRelationRepository::class, PartyRelationDoctrineORMRepository::class],
            [RoleRepository::class, RoleDoctrineORMRepository::class],
            [UserRolesRepository::class, UserRolesDoctrineORMRepository::class],
            [CourseRepository::class, CourseDoctrineORMRepository::class],
            [WebsitesRepository::class, WebsitesDoctrineORMRepository::class],
            [BlogRepository::class, BlogDoctrineORMRepository::class],
            [CurriculumRepository::class, CurriculumDoctrineORMRepository::class],
            [TagRepository::class, TagDoctrineORMRepository::class],
            [KindRepository::class, KindCacheRepository::class],
            [GroupRepository::class, GroupDoctrineORMRepository::class],
            [BookRepository::class, BookDoctrineORMRepository::class],
            [CalendarRepository::class, CalendarDoctrineORMRepository::class],
            [AssetRepository::class, AssetDoctrineORMRepository::class],
        ];
        return $repos;
    }

    private function registerExceptionHandler()
    {
        //$this->app->register(NotosExceptionHandler::class);
        $this->app->bind(
            \Illuminate\Foundation\Exceptions\Handler::class,
            NotosExceptionHandler::class
        );
    }
}
