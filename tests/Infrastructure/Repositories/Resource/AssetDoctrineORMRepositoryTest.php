<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 9/11/15
 * Time: 13:30
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories\Resource;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Guid;
use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use Bakgat\Notos\Domain\Model\Resource\AssetRepository;
use Bakgat\Notos\Infrastructure\Repositories\Identity\OrganizationDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Resource\AssetDoctrineORMRepository;
use Bakgat\Notos\Tests\DoctrineTestCase;

class AssetDoctrineORMRepositoryTest extends DoctrineTestCase
{
    /** @var AssetRepository $assetRepo */
    private $assetRepo;
    /** @var OrganizationRepository $orgRepo */
    private $orgRepo;

    public function setUp()
    {
        parent::setUp();

        $this->assetRepo = new AssetDoctrineORMRepository($this->em);
        $this->orgRepo = new OrganizationDoctrineORMRepository($this->em);

    }

    /**
     * @test
     * @group assetrepo
     */
    public function should_return_2_assets_of_type_jpg()
    {
        $klimtoren = $this->getKlimtoren();
        $assets = $this->assetRepo->assetsOfMime($klimtoren, 'image/jpeg');

        $this->assertCount(2, $assets);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Resource\Asset', $assets[0]);
    }

    /**
     * @test
     * @group assetrepo
     */
    public function should_return_5_images()
    {
        $klimtoren = $this->getKlimtoren();
        $assets = $this->assetRepo->assetsOfMime($klimtoren, 'image');

        $this->assertCount(5, $assets);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Resource\Asset', $assets[0]);
    }

    /**
     * @test
     * @group assetrepo
     */
    public function should_return_10_assets_of_klimtoren()
    {
        $klimtoren = $this->getKlimtoren();
        $assets = $this->assetRepo->all($klimtoren);

        $this->assertCount(10, $assets);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Resource\Asset', $assets[0]);
    }

    /**
     * @test
     * @group assetrepo
     */
    public function should_return_empty_set_of_type_gif()
    {
        $klimtoren = $this->getKlimtoren();
        $assets = $this->assetRepo->assetsOfMime($klimtoren, 'image/gif');

        $this->assertEmpty($assets);
    }

    /**
     * @test
     * @group assetrepo
     */
    public function should_return_asset_of_guid()
    {
        $klimtoren = $this->getKlimtoren();
        $assets = $this->assetRepo->assetsOfMime($klimtoren, 'image/jpeg');
        $tmp = $assets[0];
        $guid = $tmp->guid();

        $this->em->clear();

        $asset = $this->assetRepo->assetOfGuid($guid);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Resource\Asset', $asset);
        $this->assertTrue($guid->equals($asset->guid()));

    }

    /**
     * @test
     * @group assetrepo
     */
    public function should_return_null_when_no_guid_found()
    {
        $guid = Guid::generate();

        $asset = $this->assetRepo->assetOfGuid($guid);
        $this->assertNull($asset);
    }


    /* ***************************************************
     * PRIVATE METHODS
     * **************************************************/
    private function getKlimtoren()
    {
        $dn = new DomainName('klimtoren.be');
        $klimtoren = $this->orgRepo->organizationOfDomain($dn);
        return $klimtoren;
    }
}
