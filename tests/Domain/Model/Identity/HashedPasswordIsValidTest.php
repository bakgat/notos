<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/10/15
 * Time: 17:15
 */

namespace Bakgat\Notos\Tests\Domain\Model\Identity;

use Bakgat\Notos\Domain\Model\Identity\GenderIsValid;
use Bakgat\Notos\Domain\Model\Identity\GenderSpecification;
use Bakgat\Notos\Domain\Model\Identity\HashedPasswordIsValid;
use Bakgat\Notos\Domain\Model\Identity\HashedPasswordSpecification;
use Mockery\MockInterface;
use Mockery as m;
use Orchestra\Testbench\TestCase;

class HashedPasswordIsValidTest extends TestCase
{

    /** @var HashedPasswordSpecification $spec */
    private $spec;

    public function setUp()
    {
        parent::setUp();
        $this->spec = new HashedPasswordIsValid;
    }

    /**
     * @test
     * @group hashedpassword
     */
    public function should_return_true_if_valid()
    {
        $this->assertTrue($this->spec->isSatisfiedBy(bcrypt('password')));
    }

    /**
     * @test
     * @group hashedpassword
     */
    public function should_return_false_if_invalid()
    {
        $this->assertFalse($this->spec->isSatisfiedBy('password'));
    }
}