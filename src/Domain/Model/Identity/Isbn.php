<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 27/06/15
 * Time: 11:49
 */

namespace Bakgat\Notos\Domain\Model\Identity;


use Assert\Assertion;
use Bakgat\Notos\Domain\Model\ValueObject;

class Isbn implements ValueObject
{

    private $value;

    public function __construct($value)
    {
        Assertion::regex($value, '/^(?:ISBN(?:-1[03])?:? )?(?=[0-9X]{10}$|(?=(?:[0-9]+[- ]){3})[- 0-9X]{13}$|97[89][0-9]{10}$|(?=(?:[0-9]+[- ]){4})[- 0-9]{17}$)(?:97[89][- ]?)?[0-9]{1,5}[- ]?[0-9]+[- ]?[0-9]+[- ]?[0-9X]$/u');

        $this->value = $value;

        Assertion::true($this->isValid());
    }

    /**
     * Returns the object as string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->__normalize($this->value);
    }

    /**
     * Create a new instance from a native form
     *
     * @param mixed $native
     * @return ValueObject
     */
    public static function fromNative($native)
    {
        return new Isbn($native);
    }

    /**
     * Determine equality with another Value Object
     *
     * @param ValueObject $object
     * @return bool
     */
    public function equals(ValueObject $object)
    {
        return $this->toString() == $object->toString();
    }

    /**
     * Return the object as a string
     *
     * @return string
     */
    public function toString()
    {
        return $this->__normalize($this->value);
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return $this->value;
    }

    /**
     * Returns true if checksum-digit is valid for this ISBN
     * @return bool
     */
    public function checkDigit() {
        if($this->__type($this->value) === 10) {
            return $this->checksumDigit($this->value) === $this->__check10($this->value);
        } else {
            return $this->checksumDigit($this->value) === $this->__check13($this->value);
        }
    }

    /**
     * Converts to a string of only digits
     *
     * @param $isbn
     * @return mixed
     */
    private function __normalize()
    {

        $normalized = preg_replace('^ISBN(?:-1[03])?:? ^', '', $this->value);
        $normalized = preg_replace(('^[- ]^'), '', $normalized);
        return $normalized;
    }

    /**
     * Returns the type of ISBN: 10 or 13.
     * @return string
     */
    private function __type()
    {
        $normalized = $this->__normalize($this->value);
        return strlen($normalized);
    }

    /**
     * Returns the checksum of an ISBN-10 string
     *
     * @param $isbn
     * @return bool|int|string
     */
    private function __check10()
    {
        $normalized = $this->__normalize($this->value);
        //Verify length
        $isbnLength = strlen($normalized);
        if ($isbnLength < 9 or $isbnLength > 10) {
            return false;
        }
        //Calculate check digit
        $check = 0;
        for ($i = 0; $i < 9; $i++) {
            if ($normalized[$i] === "X" || $normalized[$i] === 'x') {
                $check += 10 * intval(10 - $i);
            } else {
                $check += intval($normalized[$i]) * intval(10 - $i);
            }
        }
        $check = 11 - $check % 11;
        if ($check === 10) {
            return 'X';
        } elseif ($check === 11) {
            return '0';
        }

        return $check;
    }

    /**
     * Returns the checksum of an ISBN-13 string
     *
     * @param $isbn
     * @return bool|int|string
     */
    private function __check13()
    {
        $normalized = $this->__normalize($this->value);
        //Verify length
        $isbnLength = strlen($normalized);
        if ($isbnLength < 12 or $isbnLength > 13) {
            return false;
        }
        //Calculate check digit
        $check = 0;
        for ($i = 0; $i < 12; $i += 2) {
            $check += substr($normalized, $i, 1);
        }
        for ($i = 1; $i < 12; $i += 2) {
            $check += 3 * substr($normalized, $i, 1);
        }
        $check = 10 - $check % 10;
        if ($check === 10) {
            return 0;
        }

        return $check;

    }

    /**
     * Returns the checksumDigit of this string
     */
    private function checksumDigit() {
        $normalized = $this->__normalize($this->value);
        return intval(substr($normalized, -1));
    }

    public function isValid()
    {
        if($this->__type() === 10) {
            return $this->checksumDigit() === $this->__check10();
        } else {
            return $this->checksumDigit() === $this->__check13();
        }
    }
}