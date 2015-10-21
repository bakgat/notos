<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 18/06/15
 * Time: 21:30
 */

namespace Bakgat\Notos\Domain\Model\Identity;

use Assert\Assertion;
use Bakgat\Notos\Domain\Model\Identity\Exceptions\GenderNotValidException;
use Bakgat\Notos\Domain\Model\ValueObject;

class Gender implements ValueObject
{
    const MALE = 'M';
    const FEMALE = 'F';
    const OTHER = 'O';

    private $value;

    public function __construct($value)
    {
        if (!$this->isValid($value)) {
            throw new GenderNotValidException($value, join(', ', GenderIsValid::$ALLOWED));
        }

        $this->value = $this->normalize($value);
    }

    /**
     * Returns the object as string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

    /**
     * Create a new instance from a native form
     *
     * @param mixed $native
     * @return ValueObject
     */
    public static function fromNative($native)
    {
        $native = Gender::normalize($native);
        return new Gender($native);
    }

    private function normalize($value)
    {
        $value = strtoupper($value);

        if ($value === 'MALE') {
            return 'M';
        }
        if ($value === 'FEMALE') {
            return 'F';
        }
        if ($value === 'OTHER') {
            return 'O';
        }
        return $value;
    }


    /**
     * Determine equality with another Value Object
     *
     * @param ValueObject $object
     * @return bool
     */
    public function equals(ValueObject $object)
    {
        return $this == $object;
    }

    /**
     * Return the object as a string
     *
     * @return string
     */
    public function toString()
    {
        return $this->value;
    }

    /* ***************************************************
     * PRIVATE VALIDATION METHODS
     * **************************************************/
    private function isValid($value) {
        $spec = new GenderIsValid();
        return $spec->isSatisfiedBy($value);
    }
}