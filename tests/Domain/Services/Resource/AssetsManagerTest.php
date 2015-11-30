<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 9/11/15
 * Time: 13:50
 */

namespace Bakgat\Notos\Tests\Domain\Services\Resource;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Guid;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Resource\Asset;
use Bakgat\Notos\Domain\Services\Resource\AssetsManager;
use Dflydev\ApacheMimeTypes\PhpRepository;
use Dflydev\ApacheMimeTypes\RepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Mockery as m;

class AssetsManagerTest extends TestCase
{
    /** @var string $file */
    private $file;
    /** @var MockInterface $assetRepo */
    private $assetRepo;
    /** @var AssetsManager $assetsManager */
    private $assetsManager;
    /** @var RepositoryInterface $phpRepository */
    private $phpRepository;
    /** @var $disk */
    private $disk;
    /** @var RepositoryInterface $orgRepo */
    private $orgRepo;

    public function setUp()
    {
        parent::setUp();

        $this->assetRepo = m::mock('Bakgat\Notos\Domain\Model\Resource\AssetRepository');
        $this->orgRepo = m::mock('Bakgat\Notos\Domain\Model\Identity\OrganizationRepository');
        $this->phpRepository = new PhpRepository();

        $this->disk = Storage::disk(config('assets.uploads.storage'));
        $this->assetsManager = new AssetsManager($this->assetRepo, $this->phpRepository, $this->orgRepo);
    }

    /**
     * @test
     * @group assetsmanager
     */
    public function should_upload_and_save_image()
    {
        $image = $this->getImage('jpg');
        $orgId = 1;
        $klimtoren = $this->getKlimtoren();

        $this->assetRepo->shouldReceive('add');

        $this->orgRepo->shouldReceive('organizationOfId')
            ->andReturn($klimtoren);

        $asset = $this->assetsManager->upload($image, $orgId);

        $this->assertFileExists($this->disk->get($asset->path()));
        $this->assertEquals('image/jpeg', $asset->mime());
    }

    /**
     * @test
     * @group assetsmanager
     */
    public function should_create_tmp_image()
    {
        $res = $this->getImage()->getMimeType();
        $this->assertEquals('image/png', $res);

        $res = $this->getImage('jpg')->getMimeType();
        $this->assertEquals('image/jpeg', $res);

        $res = $this->getImage('gif')->getMimeType();
        $this->assertEquals('image/gif', $res);
    }

    /**
     * @test
     * @group assetsmanager
     */
    public function should_return_10_assets_of_klimtoren()
    {
        $orgId = 1;
        $klimtoren = $this->getKlimtoren();
        $assets = [];
        for ($i = 0; $i < 10; $i++) {
            $assets[] = $this->renderAsset();
        }

        $this->orgRepo->shouldReceive('organizationOfId')
            ->with($orgId)
            ->andReturn($klimtoren);

        $this->assetRepo->shouldReceive('all')
            ->with($klimtoren)
            ->andReturn($assets);

        $result = $this->assetsManager->all($orgId);

        $this->assertCount(10, $result);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Resource\Asset', $result[0]);
    }

    /**
     * @test
     * @group assetsmanager
     */
    public function should_throw_org_not_found_on_all()
    {
        $this->setExpectedException('Bakgat\Notos\Domain\Model\Identity\Exceptions\OrganizationNotFoundException');

        $orgId = 999999;
        $mime_part = 'image';

        $this->orgRepo->shouldReceive('organizationOfId')
            ->with($orgId)
            ->andReturn(null);


        $result = $this->assetsManager->all($orgId);
    }

    /**
     * @test
     * @group assetsmanager
     */
    public function should_return_assets_of_type()
    {
        $orgId = 1;
        $klimtoren = $this->getKlimtoren();
        $assets = [];

        $mime_part = 'image';

        $j = 0;
        for ($i = 0; $i < 10; $i++) {
            $asset = $this->renderAsset();
            if (starts_with($asset->mime(), $mime_part)) {
                $assets[] = $asset;
                $j++;
            }
        }

        $this->orgRepo->shouldReceive('organizationOfId')
            ->with($orgId)
            ->andReturn($klimtoren);

        $this->assetRepo->shouldReceive('assetsOfType')
            ->with($klimtoren, $mime_part)
            ->andReturn($assets);

        $result = $this->assetsManager->assetsOfMimePart($orgId, $mime_part, null);

        $this->assertCount($j, $result);
        if ($j > 0) {
            $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Resource\Asset', $result[0]);
        } else {
            $this->assertEmpty($result);
        }
    }

    /**
     * @test
     * @group assetsmanager
     */
    public function should_throw_org_not_found_on_assets_of_type()
    {
        $this->setExpectedException('Bakgat\Notos\Domain\Model\Identity\Exceptions\OrganizationNotFoundException');

        $orgId = 999999;
        $mime_part = 'image';

        $this->orgRepo->shouldReceive('organizationOfId')
            ->with($orgId)
            ->andReturn(null);


        $result = $this->assetsManager->assetsOfMimePart($orgId, $mime_part, null);
    }

    public function tearDown()
    {
        if ($this->file) {
            unlink($this->file); //delete the file after each test
        }

        parent::tearDown();
    }

    /* ***************************************************
     * PRIVATE METHODS
     * **************************************************/
    private function renderAsset()
    {
        $name = new Name('asset ' . rand(0, 1000));
        $guid = Guid::generate();
        $mime = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'][rand(0, 3)];
        $klimtoren = $this->getKlimtoren();

        $asset = Asset::register($name, $guid, $mime, $klimtoren);
        $asset->setTitle($name);
        return $asset;
    }

    private function getImage($type = 'png')
    {
        $this->file = tempnam(sys_get_temp_dir(), 'upl');
        switch ($type) {
            case 'png':
                imagepng(imagecreatetruecolor(10, 10), $this->file); // create and write image/png to it
                break;
            case 'jpg':
                imagejpeg(imagecreatetruecolor(10, 10), $this->file); // create and write image/png to it
                break;
            case 'gif':
                imagegif(imagecreatetruecolor(10, 10), $this->file); // create and write image/png to it
                break;
        }

        $image = new UploadedFile(
            $this->file,
            'test_image.' . $type
        );


        return $image;
    }

    private function getKlimtoren()
    {
        $n_klimtoren = new Name('VBS De Klimtoren');
        $dn_klimtoren = new DomainName('klimtoren.be');
        $klimtoren = Organization::register($n_klimtoren, $dn_klimtoren);
        return $klimtoren;
    }
}
