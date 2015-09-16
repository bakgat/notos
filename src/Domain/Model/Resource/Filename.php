<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 27/06/15
 * Time: 21:30
 */

namespace Bakgat\Notos\Domain\Model\Resource;


use Assert\Assertion;
use Bakgat\Notos\Domain\Model\ValueObject;

class Filename implements ValueObject {

    private $value;

    public function __construct($value) {

        //Filenames must be md5 followed by jpg, gif, bmp, png
        Assertion::regex($value, '/^[a-f0-9]{32}\.(jpg|png|gif|bmp)^/i');

        $this->value = $value;
    }

    /**
     * Returns the object as string
     *
     * @return string
     */
    public function __toString() {
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
        return new Filename($native);
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

    public function directory() {
        $first = str_split($this->value, 4)[0];
        $splitted = str_split($first);
        $dir_path = implode('/', $splitted);

        return $dir_path;
    }
}