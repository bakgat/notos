<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 7/09/15
 * Time: 13:44
 */

namespace Bakgat\Notos\Tests\Domain\Model\Identity;


use Bakgat\Notos\Domain\Model\Identity\Name;
use Orchestra\Testbench\TestCase;

class NameTest extends TestCase
{
    /**
     * @test
     * @group name
     */
    public function should_require_valid_name()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        $name = new Name(12);
    }

    /**
     * @test
     * @group name
     */
    public function should_accept_valid_name()
    {
        $name = new Name('Karl Van Iseghem');
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\Name', $name);
    }

    /**
     * @test
     * @group name
     */
    public function should_create_from_native()
    {
        $name = Name::fromNative('Karl Van Iseghem');
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\Name', $name);
    }

    /**
     * @test
     * @group name
     */
    public function should_test_equality()
    {
        $one = new Name('Karl Van Iseghem');
        $two = new Name('Karl Van Iseghem');
        $three = new Name('Karl van Iseghem');

        $this->assertTrue($one->equals($two));
        $this->assertFalse($one->equals($three));
    }

    /**
     * @test
     * @group email
     */
    public function should_return_string()
    {
        $name = new Name('Karl Van Iseghem');
        $this->assertEquals('Karl Van Iseghem', $name->toString());
        $this->assertEquals('Karl Van Iseghem', (string)$name);
    }
}
