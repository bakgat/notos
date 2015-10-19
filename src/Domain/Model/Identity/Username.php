<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 17/06/15
 * Time: 22:54
 */

namespace Bakgat\Notos\Domain\Model\Identity;


use Assert\Assertion;
use Bakgat\Notos\Domain\Model\ValueObject;
use Bakgat\Notos\Domain\Model\Identity\Exceptions\UsernameNotValidException;

class Username implements ValueObject {

    private $value;

    private $user_regex = '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}^';

    public function __construct($value) {

        //Assertion::regex($value, $this->user_regex);
        if(!$this->usernameIsValid($value)) {
            throw new UsernameNotValidException($value);
        }
        $this->value = $value;
    }

    /**
     * Returns the object as string
     *
     * @return string
     */
    public function __toString() {
        return strtolower($this->value);
    }

    /**
     * Create a new instance from a native form
     *
     * @param mixed $native
     * @return ValueObject
     */
    public static function fromNative($native)
    {
        return new Username($native);
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
        return strtolower($this->value);
    }

    /* ***************************************************
     * PRIVATE VALIDATION METHODS
     * **************************************************/
    private function usernameIsValid($value)
    {
        return preg_match($this->user_regex, $value);
    }
}