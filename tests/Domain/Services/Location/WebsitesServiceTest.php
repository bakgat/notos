<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 12/11/15
 * Time: 15:38
 */

namespace Bakgat\Notos\Tests\Domain\Services\Location;


use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Location\URL;
use Bakgat\Notos\Domain\Model\Location\Website;
use Bakgat\Notos\Domain\Services\Location\WebsitesService;
use Doctrine\Common\Collections\ArrayCollection;
use Orchestra\Testbench\TestCase;
use Mockery as m;
use Mockery\MockInterface;

class WebsitesServiceTest extends TestCase
{
    /** @var MockInterface $websitesRepo */
    private $websitesRepo;
    /** @var MockInterface $tagRepo */
    private $tagRepo;
    /** @var MockInterface $currRepo */
    private $currRepo;
    /** @var WebsitesService $websitesService */
    private $websitesService;

    public function setUp()
    {
        parent::setUp();

        $this->setMocks();

        $this->websitesService = new WebsitesService($this->websitesRepo, $this->tagRepo, $this->currRepo);
    }

    /**
     * @test
     * @group websitesservice
     */
    public function should_return_all_websites()
    {
        $i = 0;
        $collection = new ArrayCollection();
        while ($i < 5) {
            $n_website = new Name('website ' . ++$i);
            $url_website = new URL('www.website' . $i . '.be');
            $website = Website::register($n_website, $url_website);
            $collection->add($website);
        }
        $this->websitesRepo->shouldReceive('all')
            ->andReturn($collection);

        $websites = $this->websitesService->all();

        $this->assertCount(5, $websites);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Location\Website', $websites[0]);
    }

    /**
     * @test
     * @group websitesservice
     */
    public function should_return_website_of_id()
    {
        $n_website = new Name('website 1');
        $url_website = new URL('www.website1.be');
        $website = Website::register($n_website, $url_website);

        $id = 1;
        $this->websitesRepo->shouldReceive('websiteOfId')
            ->with($id)
            ->andReturn($website);

        $result = $this->websitesService->websiteOfId($id);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Location\Website', $result);
        $this->assertEquals('website 1', $result->name()->toString());
    }

    /**
     * @test
     * @group websitesservice
     */
    public function should_throw_err_when_website_of_id_not_found()
    {
        $this->setExpectedException('Bakgat\Notos\Domain\Model\Location\Exceptions\WebsiteNotFoundException');
        $id = 999999;
        $this->websitesRepo->shouldReceive('websiteOfId')
            ->with($id)
            ->andReturnNull();

        $result = $this->websitesService->websiteOfId($id);
    }

    /**
     * @test
     * @group websitesservice
     */
    public function should_return_website_of_url()
    {
        $n_website = new Name('website 1');
        $url_website = new URL('www.website1.be');
        $website = Website::register($n_website, $url_website);

        $this->websitesRepo->shouldReceive('websiteOfURL')
            ->with($url_website)
            ->andReturn($website);

        $result = $this->websitesService->websiteOfUrl($url_website);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Location\Website', $result);
        $this->assertEquals('website 1', $result->name()->toString());
    }

    /**
     * @test
     * @group websitesservice
     */
    public function should_throw_err_when_website_of_url_not_found()
    {
        $this->setExpectedException('Bakgat\Notos\Domain\Model\Location\Exceptions\WebsiteNotFoundException');

        $url_website = new URL('www.website1.be');

        $this->websitesRepo->shouldReceive('websiteOfURL')
            ->with($url_website)
            ->andReturnNull();

        $result = $this->websitesService->websiteOfURL($url_website);

    }

    /* ***************************************************
     * PRIVATE METHODS
     * **************************************************/
    private function setMocks()
    {
        $this->websitesRepo = m::mock('Bakgat\Notos\Domain\Model\Location\WebsitesRepository');
        $this->tagRepo = m::mock('Bakgat\Notos\Domain\Model\Descriptive\TagRepository');
        $this->currRepo = m::mock('Bakgat\Notos\Domain\Model\Curricula\CurriculumRepository');
    }
}
