<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 4/11/15
 * Time: 09:46
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories\Descriptive;


use Bakgat\Notos\Domain\Model\Descriptive\TagName;
use Bakgat\Notos\Domain\Model\Descriptive\TagRepository;
use Bakgat\Notos\Infrastructure\Repositories\Descriptive\TagDoctrineORMRepository;
use Bakgat\Notos\Tests\DoctrineTestCase;

class TagDoctrineORMRepositoryTest extends DoctrineTestCase
{
    /** @var TagRepository $tagRepo */
    private $tagRepo;

    public function setUp()
    {
        parent::setUp();

        $this->tagRepo = new TagDoctrineORMRepository($this->em);

        $this->executor->execute($this->loader->getFixtures());
    }

    /**
     * @test
     * @group tagrepo
     */
    public function should_return_2_tags()
    {
        $tags = $this->tagRepo->all();
        $this->assertCount(2, $tags);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Descriptive\Tag', $tags[0]);
    }

    //TODO add update remove

    /**
     * @test
     * @group tagrepo
     */
    public function should_return_tag_of_name()
    {
        $n_tag = new TagName('tag1');
        $tag = $this->tagRepo->tagOfName($n_tag);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Descriptive\Tag', $tag);
        $this->assertTrue($n_tag->equals($tag->name()));
    }

    /**
     * @test
     * @group tagrepo
     */
    public function should_return_null_when_tag_of_name_not_found()
    {
        $n_tag = new TagName('foo');
        $tag = $this->tagRepo->tagOfName($n_tag);
        $this->assertNull($tag);
    }
    /**
     * @test
     * @group tagrepo
     */
    public function should_return_existing_tag()
    {
        $n_tag = new TagName('tag1');
        $tag = $this->tagRepo->tagOfNameOrCreate($n_tag);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Descriptive\Tag', $tag);
        $this->assertTrue($n_tag->equals($tag->name()));
    }
    /**
     * @test
     * @group tagrepo
     */
    public function should_create_tag_when_name_not_found()
    {
        $n_tag = new TagName('foo');
        $tag = $this->tagRepo->tagOfNameOrCreate($n_tag);

        $this->em->clear();

        $tags = $this->tagRepo->all();
        $this->assertCount(3, $tags);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Descriptive\Tag', $tag);
        $this->assertTrue($n_tag->equals($tag->name()));
    }
}
