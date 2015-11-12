<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 15/10/15
 * Time: 14:05
 */

namespace Bakgat\Notos\Tests\Domain\Model\Identity;

use Bakgat\Notos\Domain\Model\Identity\Username;
use Bakgat\Notos\Domain\Model\Identity\UsernameIsUnique;
use Bakgat\Notos\Domain\Model\Identity\UsernameSpecification;
use Mockery as m;
use Orchestra\Testbench\TestCase;
use Symfony\Bridge\Doctrine\Tests\Fixtures\User;

class UsernameIsUniqueTest extends TestCase
{
    /** @var MockInterface $userRepo */
    private $userRepo;
    /** @var UsernameSpecification $spec */
    private $spec;

    public function setUp()
    {
        $this->userRepo = m::mock('Bakgat\Notos\Domain\Model\Identity\UserRepository');
        $this->spec = new UsernameIsUnique($this->userRepo);
    }

    /**
     * @test
     * @group Specification
     */
    public function should_return_true_when_unique()
    {
        $this->userRepo->shouldReceive('userOfUsername')->andReturn(null);
        $this->assertTrue($this->spec->isSatisfiedBy(new Username('unique@klimtoren.be')));
    }

    /**
     * @test
     * @group Specification
     */
    public function should_return_false_when_not_unique()
    {
        $this->userRepo->shouldReceive('userOfUsername')->andReturn(['id' => 1]);
        $this->assertFalse($this->spec->isSatisfiedBy(new Username('karl.vaniseghem@klimtoren.be')));
    }

}