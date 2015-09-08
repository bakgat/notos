<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/06/15
 * Time: 10:46
 */

namespace Bakgat\Notos\Tests\Domain\Model\Identity;


use Bakgat\Notos\Domain\Model\Identity\HashedPassword;
use Orchestra\Testbench\TestCase;

class HashedPasswordTest extends TestCase
{
    private $pwdTest;
    private $pwdOther;

    public function __construct()
    {
        $this->pwdTest = md5('test');
        $this->pwdOther = md5('other');
    }

    /**
     * @test
     * @group hashedpassword
     */
    public function should_require_valid_password()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        $password = new HashedPassword([]);
    }

    /**
     * @test
     * @group hashedpassword
     */
    public function should_accept_valid_password()
    {
        $password = new HashedPassword($this->pwdTest);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\HashedPassword', $password);
    }

    /**
     * @test
     * @group hashedpassword
     */
    public function should_create_from_native()
    {
        $password = HashedPassword::fromNative($this->pwdTest);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\HashedPassword', $password);
    }

    /**
     * @test
     * @group hashedpassword
     */
    public function should_test_equality()
    {
        $one = new HashedPassword($this->pwdTest);
        $two = new HashedPassword($this->pwdTest);
        $three = new HashedPassword($this->pwdOther);

        $this->assertTrue($one->equals($two));
        $this->assertFalse($one->equals($three));
    }

    /**
     * @test
     * @group hashedpassword
     */
    public function should_return_as_string()
    {
        $password = new HashedPassword($this->pwdTest);
        $this->assertEquals($this->pwdTest, $password->toString());
        $this->assertEquals($this->pwdTest, (string)$password);
    }
}
