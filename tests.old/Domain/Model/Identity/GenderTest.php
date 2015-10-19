<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 7/09/15
 * Time: 13:54
 */

namespace Bakgat\Notos\Tests\Domain\Model\Identity;


use Bakgat\Notos\Domain\Model\Identity\Gender;
use Orchestra\Testbench\TestCase;

class GenderTest extends TestCase
{
    /**
     * @test
     * @group gender
     */
    public function should_require_valid_gender() {
        $this->setExpectedException('Assert\AssertionFailedException');
        $gender = new Gender('T');
    }

    /**
     * @test
     * @group gender
     */
    public function should_accept_valid_gender() {
        $gender = new Gender('Male');
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\Gender', $gender);
    }

    /**
     * @test
     * @group gender
     */
    public function should_convert_gender() {
        $gender = new Gender('Male');
        $this->assertEquals($gender->toString(), 'M');

        $gender = new Gender('Female');
        $this->assertEquals($gender->toString(), 'F');

        $gender = new Gender('Other');
        $this->assertEquals($gender->toString(), 'O');
    }
}
