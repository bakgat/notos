<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 9/11/15
 * Time: 12:03
 */

namespace Bakgat\Notos\Tests\Infrastructure\Location;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use Bakgat\Notos\Domain\Model\Location\BlogRepository;
use Bakgat\Notos\Infrastructure\Repositories\Identity\OrganizationDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Location\BlogDoctrineORMRepository;
use Bakgat\Notos\Tests\DoctrineTestCase;

class BlogDoctrineORMRepositoryTest extends DoctrineTestCase
{
    /** @var  BlogRepository $blogRepo */
    private $blogRepo;
    /** @var OrganizationRepository $orgRepo */
    private $orgRepo;

    public function setUp()
    {
        parent::setUp();

        $this->blogRepo = new BlogDoctrineORMRepository($this->em);
        $this->orgRepo = new OrganizationDoctrineORMRepository($this->em);

        $this->executor->execute($this->loader->getFixtures());
    }

    /**
     * @test
     * @group blogrepo
     */
    public function should_return_2_ordered_blogs()
    {
        $klimtoren = $this->getKlimtoren();
        $blogs = $this->blogRepo->all($klimtoren);

        $this->assertCount(2, $blogs);

        //check if is in order
        $prev = -1;
        foreach ($blogs as $blog) {
            $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Location\Blog', $blog);
            $cur = $blog->weborder();
            $this->assertEquals('blog ' . $cur, $blog->name());
            $this->assertGreaterThan($prev, $cur);

            $prev = $blog->weborder();
        }
    }

    /**
     * @test
     * @group blogrepo
     */
    public function should_return_blog_of_id()
    {
        $klimtoren = $this->getKlimtoren();
        $blogs = $this->blogRepo->all($klimtoren);

        $tmp = $blogs[0];
        $id = $tmp->id();

        $this->em->clear();

        $blog = $this->blogRepo->blogOfId($id);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Location\Blog', $blog);
        $this->assertTrue($tmp->url()->equals($blog->url()));
    }

    /**
     * @test
     * @group blogrepo
     */
    public function should_return_null_when_blog_of_id_not_found()
    {
        $id = 9999999999;
        $blog = $this->blogRepo->blogOfId($id);
        $this->assertNull($blog);
    }

    /* ***************************************************
     * PRIVATE METHODS
     * **************************************************/
    private function getKlimtoren()
    {
        $dn_klimtoren = new DomainName('klimtoren.be');
        $klimtoren = $this->orgRepo->organizationOfDomain($dn_klimtoren);
        return $klimtoren;
    }
}
