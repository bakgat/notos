<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 22/06/15
 * Time: 15:23
 */

namespace Bakgat\Notos\Tests\Domain\Model\Identity;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Orchestra\Testbench\TestCase;

class DomainNameTest extends TestCase {

    /**
     * @test
     * @group domainname
     */
    public function should_require_valid_domainname() {
        $this->setExpectedException('Assert\AssertionFailedException');
        $domain  = new DomainName('test');
    }

    /**
     * @test
     * @group domainname
     */
    public function should_accept_valid_domainname() {
        $domain = new DomainName('klimtoren.be');

        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\DomainName', $domain);
    }

    /**
     * @test
     * @group domainname
     */
    public function should_test_equality() {
        $one = new DomainName('klimtoren.be');
        $two = new DomainName('klimtoren.be');
        $three = new DomainName('www.klimtoren.be');

        $this->assertTrue($one->equals($two));
        $this->assertFalse($three->equals($one));
    }

    /**
     * @test
     * @group domainname
     */
    public function should_return_as_string() {
        $klimtoren = new DomainName('klimtoren.be');

        $this->assertEquals('klimtoren.be', $klimtoren->toString());
        $this->assertEquals('klimtoren.be', (string)$klimtoren);
    }
}
