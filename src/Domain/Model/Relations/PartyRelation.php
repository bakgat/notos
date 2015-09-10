<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 22/06/15
 * Time: 18:16
 */

namespace Bakgat\Notos\Domain\Model\Relations;

use Assert\Assertion;
use Bakgat\Notos\Domain\Model\Kind;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="p2p_relations")
 */
class PartyRelation extends Relation
{
    /**
     * @ORM\ManyToOne(targetEntity="Bakgat\Notos\Domain\Model\Identity\Party", inversedBy="relatedTo")
     * @ORM\JoinColumn(name="context")
     */
    private $context;
    /**
     * @ORM\ManyToOne(targetEntity="Bakgat\Notos\Domain\Model\Identity\Party", inversedBy="references")
     * @ORM\JoinColumn(name="reference")
     */
    private $reference;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $end;

    /**
     * @ORM\ManyToOne(targetEntity="Bakgat\Notos\Domain\Model\Kind")
     * @ORM\JoinColumn(name="kind", referencedColumnName="id")
     *
     */
    private $kind;


    public function __construct($context, $reference, DateTime $start, Kind $kind)
    {
        Assertion::isInstanceOf($context, 'Bakgat\Notos\Domain\Model\Identity\Party');
        Assertion::isInstanceOf($reference, 'Bakgat\Notos\Domain\Model\Identity\Party');

        $this->setContext($context);
        $this->setReference($reference);
        $this->setStart($start);
        $this->setKind($kind);
        $this->setCreatedAt(new DateTime);
        $this->setUpdatedAt(new DateTime);
    }

    /**
     * Registers a new party-to-party relation starting from now
     *
     * @param Party $context
     * @param Party $reference
     * @param Kind $kind
     * @return PartyRelation
     */
    public static function register($context, $reference, Kind $kind)
    {
        return new PartyRelation($context, $reference, new DateTime, $kind);
    }

    /**
     * @param context
     * @return void
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * @return Party
     */
    public function context()
    {
        return $this->context;
    }

    /**
     * @param reference
     * @return void
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return Party
     */
    public function reference()
    {
        return $this->reference;
    }

    /**
     * @param start
     * @return void
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return DateTime
     */
    public function start()
    {
        return $this->start;
    }

    /**
     * @param end
     * @return void
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * @return DateTime
     */
    public function end()
    {
        return $this->end;
    }

    /**
     * @param Kind kind
     * @return void
     */
    public function setKind(Kind $kind)
    {
        $this->kind = $kind;
    }

    /**
     * @return Kind
     */
    public function kind()
    {
        return $this->kind;
    }

    /**
     * Ends a relation.
     */
    public function destroy()
    {
        $this->setEnd(new DateTime);
    }


}