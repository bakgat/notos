<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/11/15
 * Time: 15:01
 */

namespace Bakgat\Notos\Test\Http\Controllers\Resource;


use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Guid;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Resource\Asset;
use Bakgat\Notos\Tests\ControllersTestCase;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Mockery\MockInterface;
use Mockery as m;

class AssetsControllerTest extends ControllersTestCase
{
    /** @var MockInterface $assetsManager */
    private $assetsManager;
    /** @var MockInterface $orgRepo */
    private $orgRepo;

    public function setUp()
    {
        parent::setUp();
        $this->assetsManager = $this->mock('Bakgat\Notos\Domain\Services\Resource\AssetsManager');
        $this->orgRepo = $this->mock('Bakgat\Notos\Domain\Model\Identity\OrganizationRepository');
    }


    /**
     * @test
     * @group assetscontroller
     */
    public function should_post_valid_data()
    {
        $uploadedFile = $this->uploadedFiles();

        $klimtoren = $this->getKlimtoren();
        $asset = $this->getAsset();

        $this->orgRepo->shouldReceive('organizationOfId')
            ->with(1)
            ->andReturn($klimtoren);

        $this->assetsManager->shouldReceive('upload')
            ->with($uploadedFile, $klimtoren)
            ->andReturn($asset);

        $this->call('POST', '/api/organization/1/upload/',
            [], [], ['asset' => $uploadedFile]);

        //Test creating folder is done in assetsmanager test
        //now just check the route, returned object and conversion to json
        $this->assertResponseStatus(200);

        $this->seeJson([
            'name' => 'image-1.jpg',
            'path' => $asset->path(),
            'guid' => $asset->guid()->toString(),
            'title' => 'image-1.jpg',
        ]);
    }

    /**
     * @test
     * @group assetscontroller
     */
    public function should_throw_err_when_org_not_found()
    {
        $this->setExpectedException('Bakgat\Notos\Domain\Model\Identity\Exceptions\OrganizationNotFoundException');

        $uploadedFile = $this->uploadedFiles();

        $this->orgRepo->shouldReceive('organizationOfId')
            ->with(999999)
            ->andReturnNull();

        $this->call('POST', '/api/organization/999999/upload/', [], [], ['asset' => $uploadedFile]);

        //$this->assertResponseStatus(404);
    }

    /* ***************************************************
     * PRIVATE METHODS
     * **************************************************/
    private function uploadedFiles()
    {
        $uploadedFile = $this->mock(
            '\Symfony\Component\HttpFoundation\File\UploadedFile',
            [
                'getClientOriginalName' => 'image-1.jpg',
                'getClientOriginalExtension' => 'jpg',
            ]
        );
        return $uploadedFile;
    }

    private function getKlimtoren()
    {
        $n_org = new Name('VBS De Klimtoren');
        $dn_org = new DomainName('klimtoren.be');
        $organization = Organization::register($n_org, $dn_org);
        return $organization;
    }

    /**
     * @return Asset
     */
    private function getAsset()
    {
        $klimtoren = $this->getKlimtoren();

        $guid = Guid::generate();
        $name = new Name('image-1.jpg');
        $mime = 'image/jpeg';

        $asset = new Asset($guid, $name, $mime, $klimtoren);
        $asset->setTitle($name->toString());
        return $asset;
    }

    private function mock($class)
    {
        $mock = m::mock($class);
        $this->app->instance($class, $mock);
        return $mock;
    }
}
