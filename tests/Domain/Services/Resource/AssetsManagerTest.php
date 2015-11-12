<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 9/11/15
 * Time: 13:50
 */

namespace Bakgat\Notos\Tests\Domain\Services\Resource;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\Organization;
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

    public function setUp()
    {
        parent::setUp();

        $this->assetRepo = m::mock('Bakgat\Notos\Domain\Model\Resource\AssetRepository');
        $this->phpRepository = new PhpRepository();

        $this->disk = Storage::disk(config('assets.uploads.storage'));
        $this->assetsManager = new AssetsManager($this->assetRepo, $this->phpRepository);
    }

    /**
     * @test
     * @group assetsmanager
     */
    public function should_upload_and_save_image()
    {
        $image = $this->getImage('jpg');
        $klimtoren = $this->getKlimtoren();

        $this->assetRepo->shouldReceive('add');

        $asset = $this->assetsManager->upload($image, $klimtoren);

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


    public function tearDown()
    {
        unlink($this->file); //delete the file after each test

        parent::tearDown();
    }

    /* ***************************************************
     * PRIVATE METHODS
     * **************************************************/
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
