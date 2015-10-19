<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 15/10/15
 * Time: 15:32
 */

namespace Bakgat\Notos\Tests\Domain\Model\Description;


use Bakgat\Notos\Domain\Model\Descriptive\TagName;
use Orchestra\Testbench\TestCase;

class TagNameTest extends TestCase
{
    /**
     * @test
     * @group tagname
     */
    /*public function should_require_valid_tagname()
    {
        $this->setExpectedException('Bakgat\Notos\Domain\Model\Descriptive\Exceptions\TagNameNotValidException');
        $tag = new TagName('12.');
    }*/

    /**
     * @test
     * @group tagname
     */
    public function should_accept_valid_tagname()
    {
        $tagName = new TagName('tag with space');
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Descriptive\TagName', $tagName);
    }


    /**
     * @test
     * @group tagname
     */
    public function should_create_from_native()
    {
        $tagname = TagName::fromNative('tag with space');
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Descriptive\TagName', $tagname);
    }

    /**
     * @test
     * @group tagname
     */
    public function should_test_equality()
    {
        $one = new TagName('tag with space');
        $two = new TagName('tag with space');
        $three = new TagName('tag with spaces'); //minor diff + -s

        $this->assertTrue($one->equals($two));
        $this->assertFalse($one->equals($three));
    }

    /**
     * @test
     * @group tagname
     */
    public function should_lowercase_tags() {
        $upper = new TagName('TagName');
        $lower = new TagName('tag Name');

        $this->assertEquals('tagname', $upper->toString());
        $this->assertNotEquals('TagName', $upper->toString());

        $this->assertEquals('tag name', $lower->toString());
    }

    /**
     * @test
     * @group tagname
     */
    public function should_return_string()
    {
        $tagname = new TagName('TagName');
        $this->assertEquals('tagname', $tagname->toString());
        $this->assertEquals('tagname', (string)$tagname);
    }
}