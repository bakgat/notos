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
    public function should_require_valid_tagname()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        $tag = new TagName('12.');
    }

    /**
     * @test
     * @group tagname
     */
    public function should_accept_valid_tagname()
    {
        $tagName = new TagName('tag with space');
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Descriptive\TagName', $tagName);
    }
}