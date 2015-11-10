<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 9/11/15
 * Time: 13:30
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories\Resource;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
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
    public function should_return_3_assets_of_type_jpg()
    {

    }

    /**
     * @test
     * @group assetrepo
     */
    public function should_return_10_assets_of_klimtoren()
    {

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
