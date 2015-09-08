<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 7/09/15
 * Time: 14:03
 */

namespace Bakgat\Notos\Tests\Domain\Model\Identity;


use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\Party;
use Orchestra\Testbench\TestCase;

class PartyTest extends TestCase
{
    /** @var Name $firstName */
    private $firstName;
    /** @var Name $lastName */
    private $lastName;

    public function setUp()
    {
        $this->firstName = new Name('Karl');
        $this->lastName = new Name('Van Iseghem');
    }

    /**
     * @test
     * @group party
     */
    public function should_create_new_party()
    {
        $party = new Party($this->lastName);
        $party->setFirstName($this->firstName);

        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\Party', $party);
    }
}
