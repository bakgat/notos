<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 12/11/15
 * Time: 15:38
 */

namespace Bakgat\Notos\Tests\Domain\Services\Location;


use Bakgat\Notos\Domain\Model\Curricula\Course;
use Bakgat\Notos\Domain\Model\Curricula\Objective;
use Bakgat\Notos\Domain\Model\Curricula\Structure;
use Bakgat\Notos\Domain\Model\Descriptive\Tag;
use Bakgat\Notos\Domain\Model\Descriptive\TagName;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Location\URL;
use Bakgat\Notos\Domain\Model\Location\Website;
use Bakgat\Notos\Domain\Services\Location\WebsitesService;
use Bakgat\Notos\Domain\Model\Curricula\Curriculum;
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

    /**
     * @test
     * @group websitesservice
     */
    public function should_add_valid_data()
    {
        $data = $this->getKlimtornWebsiteData();

        //TAGS ------------------------------------------
        $n_tag_foo = new TagName('foo');
        $tag_foo = Tag::register($n_tag_foo);

        //OBJECTIVES ------------------------------------
        $maths = new Course(new Name('wiskunde'));
        $curr = new Curriculum($maths, 2009);
        $struc = Structure::register($curr, new Name('structure'), 'chapter');

        $n_obj1 = new Name('objective 1');
        $obj1 = Objective::register($n_obj1, 'D1', $struc);
        $n_obj2 = new Name('objective 2');
        $obj2 = Objective::register($n_obj2, 'D2', $struc);

        //MOCKS -----------------------------------------
        $this->websitesRepo->shouldReceive('add');

        $this->tagRepo->shouldReceive('tagOfNameOrCreate')
            ->twice()
            ->andReturn($tag_foo);


        $this->currRepo->shouldReceive('objectiveOfId')
            ->with(1)
            ->andReturn($obj1);
        $this->currRepo->shouldReceive('objectiveOfId')
            ->with(2)
            ->andReturn($obj2);

        //SERVICE CALL ------------------------------------
        $website = $this->websitesService->add($data);

        //ASSERTS -----------------------------------------
        $this->assertEquals('VBS De Klimtoren', $website->name());
        $this->assertEquals('http://www.klimtoren.be/', $website->url());

        $this->assertCount(2, $website->getTags());
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Descriptive\Tag', $website->getTags()[0]);

        $this->assertCount(2, $website->getObjectives());
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Curricula\Objective', $website->getObjectives()[0]);
    }

    /**
     * @test
     * @group websitesservice
     */
    public function should_throw_err_when_url_is_invalid()
    {
        $this->setExpectedException('Bakgat\Notos\Domain\Model\Location\Exceptions\URLNotValidException');

        $data = [
            'name' => 'VBS De Klimtoren',
            'url' => 'foo@'
        ];

        $this->websitesService->add($data);
    }

    /**
     * @test
     * @group websitesservice
     */
    public function should_require_valid_data()
    {
        $this->setExpectedException('Bakgat\Notos\Exceptions\UnprocessableEntityException');

        $data = [];
        $this->websitesService->add($data);
    }

    /**
     * @test
     * @group websitesservice
     */
    public function should_update_valid_data()
    {
        $id = 1;
        $data = $this->getKlimtornWebsiteData();

        $website = $this->getKlimtorenWebsite();

        //TAGS ------------------------------------------
        $n_tag_foo = new TagName('foo');
        $tag_foo = Tag::register($n_tag_foo);

        //OBJECTIVES ------------------------------------
        $maths = new Course(new Name('wiskunde'));
        $curr = new Curriculum($maths, 2009);
        $struc = Structure::register($curr, new Name('structure'), 'chapter');

        $n_obj1 = new Name('objective 1');
        $obj1 = Objective::register($n_obj1, 'D1', $struc);
        $n_obj2 = new Name('objective 2');
        $obj2 = Objective::register($n_obj2, 'D2', $struc);


        //MOCKS -----------------------------------------
        $this->websitesRepo->shouldReceive('websiteOfId')
            ->with($id)
            ->andReturn($website);

        $this->tagRepo->shouldReceive('tagOfNameOrCreate')
            ->twice()
            ->andReturn($tag_foo);


        $this->currRepo->shouldReceive('objectiveOfId')
            ->with(1)
            ->andReturn($obj1);
        $this->currRepo->shouldReceive('objectiveOfId')
            ->with(2)
            ->andReturn($obj2);

        $this->websitesRepo->shouldReceive('update');

        //SERVICE CALL ------------------------------------
        $this->assertEquals('FTP De Klimtoren', $website->name());
        $this->assertEquals('http://ftp.klimtoren.be/', $website->url());

        $result = $this->websitesService->update($id, $data);

        $this->assertEquals('VBS De Klimtoren', $website->name());
        $this->assertEquals('http://www.klimtoren.be/', $website->url());

        $this->assertCount(2, $result->getTags());
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Descriptive\Tag', $result->getTags()[0]);

        $this->assertCount(2, $result->getObjectives());
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Curricula\Objective', $result->getObjectives()[0]);
    }

    /**
     * @test
     * @group websitesservice
     */
    public function should_require_valid_data_on_update()
    {
        $this->setExpectedException('Bakgat\Notos\Exceptions\UnprocessableEntityException');

        $id = 1;
        $data = [];
        $website = $this->getKlimtorenWebsite();

        $this->websitesRepo->shouldReceive('websiteOfId')
            ->with($id)
            ->andReturn($website);

        $this->websitesService->update($id, $data);
    }

    /**
     * @test
     * @group websitesservice
     */
    public function should_throw_website_not_found()
    {
        $this->setExpectedException('Bakgat\Notos\Domain\Model\Location\Exceptions\WebsiteNotFoundException');

        $id = 99999;
        $this->websitesRepo->shouldReceive('websiteOfId')
            ->with($id)
            ->andReturnNull();

        $data = $this->getKlimtornWebsiteData();

        $this->websitesService->update($id, $data);
    }

    /**
     * @test
     * @group websitesservice
     */
    public function should_throw_url_not_valid_on_update()
    {
        $this->setExpectedException('Bakgat\Notos\Domain\Model\Location\Exceptions\URLNotValidException');

        $id = 1;
        $data = ['name' => 'VBS De Klimtoren', 'url' => 'foo@'];
        $website = $this->getKlimtorenWebsite();

        $this->websitesRepo->shouldReceive('websiteOfId')
            ->with($id)
            ->andReturn($website);

        $this->websitesService->update($id, $data);
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

    /**
     * @return Website
     */
    private function getKlimtorenWebsite()
    {
        $n_website = new Name('FTP De Klimtoren');
        $url_website = new URL('ftp.klimtoren.be');
        $website = Website::register($n_website, $url_website);
        $website->setDescription('Test 1');
        return $website;
    }

    /**
     * @return array
     */
    private function getKlimtornWebsiteData()
    {
        $data = [
            'name' => 'VBS De Klimtoren',
            'url' => 'www.klimtoren.be',
            'description' => 'Het portaal van De Klimtoren',
            'tags' => [['name' => 'portaal'], ['name' => 'homepage']],
            'objectives' => [['id' => 1], ['id' => 2]]
        ];
        return $data;
    }
}
