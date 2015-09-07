<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/06/15
 * Time: 09:55
 */

namespace Bakgat\Notos\Tests\Domain\Model\Identity;


use Bakgat\Notos\Domain\Model\Identity\Username;

class UsernameTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @group username
     */
    public function should_require_valid_username()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        $username = new Username('john');
    }

    /**
     * @test
     * @group username
     */
    public function should_accept_valid_username()
    {
        $username = new Username('karl.vaniseghem@klimtoren.be');
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\Username', $username);
    }

    /**
     * @test
     * @group username
     */
    public function should_create_from_native()
    {
        $username = Username::fromNative('philipbrown@klimtoren.be');
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\Username', $username);
    }

    /**
     * @test
     * @group username
     */
    public function should_test_equality()
    {
        $one = new Username('karl.vaniseghem@klimtoren.be');
        $two = new Username('karl.vaniseghem@klimtoren.be');
        $three = new Username('john@klimtoren.be');
        $this->assertTrue($one->equals($two));
        $this->assertFalse($one->equals($three));
    }

    /**
     * @test
     * @group username
     */
    public function should_return_as_string()
    {
        $username = new Username('karl.vaniseghem@klimtoren.be');
        $this->assertEquals('karl.vaniseghem@klimtoren.be', $username->toString());
        $this->assertEquals('karl.vaniseghem@klimtoren.be', (string)$username);
    }
}
