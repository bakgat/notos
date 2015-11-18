<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 7/09/15
 * Time: 13:39
 */

namespace Bakgat\Notos\Domain\Model\Identity;


use Assert\Assertion;
use Bakgat\Notos\Domain\Model\Identity\Exceptions\GuidNotValidException;
use Bakgat\Notos\Domain\Model\ValueObject;

class Guid implements ValueObject
{
    private $value;

    public function __construct($value)
    {
        if (!$this->isValidMd5($value)) {
            throw new GuidNotValidException($value);
        }
        $this->value = $value;

    }

    /**
     * Generate a unique identifier
     * @return static
     */
    public static function generate()
    {
        return new static(md5(time() . rand(1, 100)));
    }

    /**
     * Convert Guid to path string
     * @return string
     */
    public function toPath()
    {
        $first = str_split($this->toString(), 4)[0];
        $splitted = str_split($first);
        $dir_path = implode('/', $splitted);

        $path = '/' . ltrim($dir_path, '/');

        return $path . '/' . $this->toString();
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
        return new Guid($native);
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
     *
     * **************************************************/
    private function isValidMd5($md5 = '')
    {
        return preg_match('/^[a-f0-9]{32}$/', $md5);
    }
}