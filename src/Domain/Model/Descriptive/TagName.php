<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 17/06/15
 * Time: 22:54
 */

namespace Bakgat\Notos\Domain\Model\Descriptive;


use Assert\Assertion;
use Bakgat\Notos\Domain\Model\ValueObject;

class TagName implements ValueObject {

    private $value;

    public function __construct($value) {

        //only allow a-z A-Z 0-9 and spaces
        Assertion::regex($value, '/^[ \w#-]+$/');

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
        return new TagName($native);
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
}