<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 15/10/15
 * Time: 14:34
 */

namespace Bakgat\Notos\Tests\Domain\Model\Description;

use Bakgat\Notos\Domain\Model\Descriptive\TagName;
use Bakgat\Notos\Domain\Model\Descriptive\TagNameIsUnique;
use Bakgat\Notos\Domain\Model\Descriptive\TagNameSpecification;
use Mockery as m;
use Orchestra\Testbench\TestCase;

class TagNameIsUniqueTest extends TestCase
{
    private $tagRepo;
    /** @var TagNameSpecification $spec */
    private $spec;

    public function setUp() {
        parent::setUp();

        $this->tagRepo = m::mock('Bakgat\Notos\Domain\Model\Descriptive\TagRepository');
        $this->spec = new TagNameIsUnique($this->tagRepo);
    }

    /**
     * @test
     * @group Specification
     */
    public function should_return_true_when_unique() {
        $this->tagRepo->shouldReceive('tagOfName')->andReturn(null);
        $this->assertTrue($this->spec->isSatisfiedBy(new TagName('unqiue')));
    }
    /**
     * @test
     * @group Specification
     */
    public function should_return_false_when_not_unique() {
        $this->tagRepo->shouldReceive('tagOfName')->andReturn(['id' => 1]);
        $this->assertFalse($this->spec->isSatisfiedBy(new TagName('non_unique')));
    }
}