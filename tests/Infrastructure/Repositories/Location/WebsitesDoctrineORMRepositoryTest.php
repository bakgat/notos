<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 1/11/15
 * Time: 20:29
 */

namespace Bakgat\Notos\Tests\Infrastructure\Location;


use Bakgat\Notos\Domain\Model\Location\URL;
use Bakgat\Notos\Domain\Model\Location\WebsitesRepository;
use Bakgat\Notos\Infrastructure\Repositories\Location\WebsitesDoctrineORMRepository;
use Bakgat\Notos\Seeds\Fixtures\CourseFixtures;
use Bakgat\Notos\Seeds\Fixtures\WebsiteFixtures;
use Bakgat\Notos\Tests\DoctrineTestCase;
use Bakgat\Notos\Tests\Fixtures\TestFixtures;

class WebsitesDoctrineORMRepositoryTest extends DoctrineTestCase
{
    /** @var WebsitesRepository $websiteRepo */
    private $websiteRepo;

    public function setUp()
    {
        parent::setUp();

        $this->websiteRepo = new WebsitesDoctrineORMRepository($this->em);

        $this->loader->addFixture(new TestFixtures);
        $this->executor->execute($this->loader->getFixtures());
    }


    /**
     * @test
     * @group websitesrepo
     */
    public function should_return_12_websites()
    {
        $sites = $this->websiteRepo->all();
        //2 in websitesfixtures / 10 in testfixtures
        $this->assertCount(10, $sites);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Location\Website', $sites[0]);
    }

    /**
     * @test
     * @group websitesrepo
     */
    public function should_return_fully_websites()
    {
        $sites = $this->websiteRepo->full();
        $this->assertCount(10, $sites);
    }

    /**
     * @test
     * @group websitesrepo
     */
    public function should_return_website_with_id()
    {
        $tmp = $this->websiteRepo->websiteOfURL(new URL('www.google.be'));
        $id = $tmp->id();
        $this->em->clear();

        $site = $this->websiteRepo->websiteofId($id);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Location\Website', $site);
        $this->assertTrue($tmp->url()->equals($site->url()));
    }

    /**
     * @test
     * @group websitesrepo
     */
    public function should_return_null_when_website_not_found_with_id()
    {
        $id = 99999999;

        $site = $this->websiteRepo->websiteofId($id);
        $this->assertNull($site);
    }

    /**
     * @test
     * @group websitesrepo
     */
    public function should_return_site_with_url()
    {
        $site = $this->websiteRepo->websiteofUrl(new URL('www.google.be'));
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Location\Website', $site);
        $this->assertEquals('http://www.google.be/', $site->url()->toString());
    }

    /**
     * @test
     * @group websitesrepo
     */
    public function should_return_null_when_website_not_found_with_url()
    {
        $url = new URL('foo.be');

        $site = $this->websiteRepo->websiteOfURL($url);
        $this->assertNull($site);
    }

    //TODO ADD AND UPDATE FUNCTIONS

    //TODO ClearObjetives, clearTags

    //TODO objectives, tags, ... in fixtures
}
