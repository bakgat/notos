<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 22/06/15
 * Time: 18:17
 */

namespace Bakgat\Notos\Domain\Model\Relations;

use Atrauzzi\LaravelDoctrine\Util\Time;
use Doctrine\ORM\Mapping as ORM;


/**
 *
 * @ORM\MappedSuperclass
 *
 */
class Relation {
    use Time;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
}