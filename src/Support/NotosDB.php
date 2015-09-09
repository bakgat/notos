<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 26/06/15
 * Time: 10:02
 */

namespace Bakgat\Notos\Support;

use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use DateTime;
use Doctrine\ORM\Query\Expr;

class NotosDB
{
    public static function ObjAlive($qb, $param)
    {
        return $qb->andWhere(
            $qb->expr()->orX(
                $qb->expr()->isNull('pr.end'),
                $qb->expr()->gt('pr.end', $param)),
            $qb->expr()->lte('pr.start', $param));

    }

    public static function ObjDestroyed($qb, $param)
    {
        return $qb->orX(
            $qb->expr()->isNotNull('pr.end'),
            $qb->expr()->lte('pr.end', $param)
        );
    }

}