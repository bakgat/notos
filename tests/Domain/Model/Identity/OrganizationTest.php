<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 22/06/15
 * Time: 12:03
 */

namespace Bakgat\Notos\Tests\Domain\Model\Identity;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\Organization;

class OrganizationTest extends \PHPUnit_Framework_TestCase
{

    /** @var  Name */
    private $name;
    /** @var  DomainName */
    private $domain_name;
    /** @var  string */
    private $avatar;

    public function setUp()
    {
        $this->name = new Name('VBS De Klimtoren');
        $this->domain_name = new DomainName('klimtoren.be');
        $this->avatar = null; //TODO: support avatars
    }

    /**
     * @test
     * @group organization
     */
    public function should_create_new_organization()
    {
        $org = Organization::register($this->name);
        $org->setDomainName($this->domain_name);

        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\Organization', $org);
        $this->assertEquals($this->name, $org->name());
        $this->assertEquals($this->domain_name, $org->domainName());
    }


}
