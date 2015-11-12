<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/10/15
 * Time: 17:15
 */

namespace Bakgat\Notos\Tests\Domain\Model\Identity;


use Bakgat\Notos\Domain\Model\Identity\Username;
use Bakgat\Notos\Domain\Model\Identity\UsernameIsUnique;
use Bakgat\Notos\Domain\Model\Identity\UsernameSpecification;
use Mockery\MockInterface;
use Mockery as m;
use Orchestra\Testbench\TestCase;

class UsernameIsUniqueTest extends TestCase
{
    /** @var  MockInterface $userRepo */
    private $userRepo;
    /** @var UsernameSpecification $spec */
    private $spec;

    public function setUp()
    {
        parent::setUp();
        $this->userRepo = m::mock('Bakgat\Notos\Domain\Model\Identity\UserRepository');
        $this->spec = new UsernameIsUnique($this->userRepo);
    }

    /**
     * @test
     * @group username
     */
    public function should_return_true_if_unique()
    {
        $this->userRepo->shouldReceive('userOfUsername')->andReturnNull();
        $this->assertTrue($this->spec->isSatisfiedBy(new Username('unique@klimtoren.be')));
    }

    /**
     * @test
     * @group username
     */
    public function should_return_false_if_not_unique()
    {
        $this->userRepo->shouldReceive('userOfUsername')->andReturn(['id' => 1]);
        $this->assertFalse($this->spec->isSatisfiedBy(new Username('not_unique@klimtoren.be')));
    }
}