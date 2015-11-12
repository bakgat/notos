<?php
namespace Bakgat\Notos\Test\Domain\Model\Location;

use Bakgat\Notos\Domain\Model\Location\URL;
use Orchestra\Testbench\TestCase;

/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/10/15
 * Time: 15:44
 */
class URLTest extends TestCase
{
    /**
     * @test
     * @group url
     */
    public function should_require_valid_url() {
        $this->setExpectedException('Bakgat\Notos\Domain\Model\Location\Exceptions\URLNotValidException');
        $url  = new URL('@');
    }

    /**
     * @test
     * @group url
     */
    public function should_accept_valid_url() {
        $url = new URL('klimtoren.be');

        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Location\URL', $url);
    }

    /**
     * @test
     * @group url
     */
    public function should_test_equality() {
        $one = new URL('www.klimtoren.be');
        $two = new URL('http://www.klimtoren.be');
        $three = new URL('ww.klimtoren.be/'); //ww instead of www

        $this->assertTrue($one->equals($two));
        $this->assertFalse($three->equals($one));
    }

    /**
     * @test
     * @group url
     */
    public function should_return_as_string() {
        $klimtoren = new URL('www.klimtoren.be');

        $this->assertEquals('http://www.klimtoren.be/', $klimtoren->toString());
        $this->assertEquals('http://www.klimtoren.be/', (string)$klimtoren);
    }
}