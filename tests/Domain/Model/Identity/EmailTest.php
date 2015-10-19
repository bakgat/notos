<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/06/15
 * Time: 11:04
 */

namespace Bakgat\Notos\Tests\Domain\Model\Identity;


use Bakgat\Notos\Domain\Model\Identity\Email;
use Orchestra\Testbench\TestCase;

class EmailTest extends TestCase
{

    /**
     * @test
     * @group email
     */
    public function should_require_valid_email()
    {
        $this->setExpectedException('Bakgat\Notos\Domain\Model\Identity\Exceptions\EmailNotValidException');
        $email = new Email('karl');
    }

    /**
     * @test
     * @group email
     */
    public function should_accept_valid_email()
    {
        $email = new Email('karl.vaniseghem@klimtoren.be');
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\Email', $email);
    }

    /**
     * @test
     * @group email
     */
    public function should_create_from_native()
    {
        $email = Email::fromNative('karl.vaniseghem@klimtoren.be');
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\Email', $email);
    }

    /**
     * @test
     * @group email
     */
    public function should_test_equality()
    {
        $one = new Email('karl.vaniseghem@klimtoren.be');
        $two = new Email('karl.vaniseghem@klimtoren.be');
        $three = new Email('ulrike.drieksens@gmail.com');

        $this->assertTrue($one->equals($two));
        $this->assertFalse($one->equals($three));
    }

    /**
     * @test
     * @group email
     */
    public function should_return_string()
    {
        $email = new Email('karl.vaniseghem@klimtoren.be');
        $this->assertEquals('karl.vaniseghem@klimtoren.be', $email->toString());
        $this->assertEquals('karl.vaniseghem@klimtoren.be', (string)$email);
    }
}
