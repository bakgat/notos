<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/10/15
 * Time: 17:56
 */

namespace Bakgat\Notos\Tests\Domain\Model\Identity;


use Bakgat\Notos\Domain\Model\Identity\Guid;
use Bakgat\Notos\Domain\Model\Identity\Isbn;
use Orchestra\Testbench\TestCase;

class GuidTest extends TestCase
{
    /**
     * @test
     * @group guid
     */
    public function should_require_valid_guid()
    {
        $this->setExpectedException('Bakgat\Notos\Domain\Model\Identity\Exceptions\GuidNotValidException');
        $isbn = new Guid('0987097000');
    }

    /**
     * @test
     * @group guid
     */
    public function should_accept_valid_guid()
    {
        $guid = new Guid('d41d8cd98f00b204e9800998ecf8427e');
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\Guid', $guid);
    }

    /**
     * @test
     * @group guid
     */
    public function should_generate_valid_guid()
    {
        $guid = Guid::generate();
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\Guid', $guid);
    }

    /**
     * @test
     * @group guid
     */
    public function should_create_from_native()
    {
        $guid = Guid::fromNative('d41d8cd98f00b204e9800998ecf8427e');
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\Guid', $guid);
    }

    /**
     * @test
     * @group guid
     */
    public function should_test_equality()
    {
        $guid1 = Guid::fromNative('d41d8cd98f00b204e9800998ecf8427e');
        $guid2 = new Guid('d41d8cd98f00b204e9800998ecf8427e');
        $this->assertTrue($guid1->equals($guid2));
    }

    public function should_return_string()
    {
        $guid1 = new Guid('d41d8cd98f00b204e9800998ecf8427e');
        $this->assertEquals('d41d8cd98f00b204e9800998ecf8427e', $guid1->toString());
        $this->assertEquals('d41d8cd98f00b204e9800998ecf8427e', (string)$guid1);
    }
}