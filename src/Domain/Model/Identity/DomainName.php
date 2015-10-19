<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 22/06/15
 * Time: 15:14
 */

namespace Bakgat\Notos\Domain\Model\Identity;


use Assert\Assertion;
use Bakgat\Notos\Domain\Model\Identity\Exceptions\DomainNameNotValid;
use Bakgat\Notos\Domain\Model\ValueObject;
use Bakgat\Notos\Exceptions\PreconditionFailedException;

class DomainName implements ValueObject {

    private $value;

    private $domain_regex = '^((?!-)[A-Za-z0-9-]{1,63}(?<!-)\\.)+[A-Za-z]{2,6}^';

    /**
     * @param $value
     * @throws PreconditionFailedException
     */
    public function __construct($value) {
        if(!$this->isValidDomainName($value)) {
            throw new DomainNameNotValid($value);
        }

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
        return new DomainName($native);
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
     * PRIVATE VALIDATION
     * **************************************************/
    private function isValidDomainName($value)
    {
        return preg_match($this->domain_regex, $value);
    }
}