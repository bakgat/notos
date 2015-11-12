<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/10/15
 * Time: 17:56
 */

namespace Bakgat\Notos\Tests\Domain\Model\Identity;


use Bakgat\Notos\Domain\Model\Identity\Isbn;
use Orchestra\Testbench\TestCase;

class IsbnTest extends TestCase
{
    /**
     * @test
     * @group isbn
     */
    public function should_require_valid_isbn()
    {
        $this->setExpectedException('Bakgat\Notos\Domain\Model\Identity\Exceptions\IsbnNotValidException');
        $isbn = new Isbn('0987097000');
    }

    /**
     * @test
     * @group isbn
     */
    public function should_accept_valid_isbn()
    {
        $isbn = new Isbn('9789027439642');
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\Isbn', $isbn);
    }

    public function should_create_from_native()
    {

    }
}