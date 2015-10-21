<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/10/15
 * Time: 14:18
 */

namespace Bakgat\Notos\Domain\Model\Identity\Exceptions;


use Bakgat\Notos\Exceptions\PreconditionFailedException;

class HashedPasswordNotValidException extends PreconditionFailedException
{
}