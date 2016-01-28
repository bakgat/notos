<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 15/09/15
 * Time: 23:04
 */

namespace Bakgat\Notos\Infrastructure\Repositories\Identity;


use Bakgat\Notos\Domain\Model\Identity\Group;
use Bakgat\Notos\Domain\Model\Identity\GroupRepository;
use Bakgat\Notos\Domain\Model\Kind;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Illuminate\Support\Facades\Cache;

class GroupDoctrineORMRepository implements GroupRepository
{
    /** @var EntityManager $em */
    private $em;
    /** @var string $groupClass */
    private $groupClass;


    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->groupClass = 'Bakgat\Notos\Domain\Model\Identity\Group';
    }

    /**
     * @param $name
     * @return Group
     */
    public function groupOfName($name)
    {
        $cache_key = md5('group.' . $name);
        if (Cache::has($cache_key)) {
            return Cache::get($cache_key);
        }

        $group = $this->em->getRepository($this->groupClass)
            ->findOneBy(['lastName' => $name]);
        Cache::forever($cache_key, $group);

        return $group;
    }

    /**
     * Get all groups of a kind
     *
     * @param Kind $kind
     * @param integer|null $orgId
     * @return mixed
     */
    public function groupsOfKind(Kind $kind, $orgId = null)
    {

        if ($orgId) {
            $sql = <<<EOT
                    SELECT
                        p.id AS id,
                        p.lastName,
                        g.description,
                        g.avatar
                    FROM groups g
                    INNER JOIN parties p ON g.id = p.id
                    INNER JOIN p2p_relations rel ON p.id = rel.context
                    WHERE p.kind_id = ? AND rel.reference =?
                    ORDER BY p.id
EOT;
        } else {
            $sql = <<<EOT
                    SELECT
                        p.id AS id,
                        p.firstName,
                        p.lastName,
                        g.description,
                        g.avatar
                    FROM groups g
                    INNER JOIN parties p ON g.id = p.id
                    WHERE p.kind_id = ?
                    ORDER BY p.id
EOT;
        }


        $rsm = new ResultSetMappingBuilder($this->em);
        $rsm->addEntityResult('Bakgat\Notos\Domain\Model\Identity\Party', 'p');
        $rsm->addEntityResult('Bakgat\Notos\Domain\Model\Identity\Group', 'g');
        $rsm->addFieldResult('p', 'id', 'id');
        $rsm->addFieldResult('p', 'lastName', 'lastName');
        $rsm->addFieldResult('g', 'description', 'description');

        $qb = $this->em->createNativeQuery($sql, $rsm)
            ->setParameter(1, $kind->id());

        if ($orgId) {
            $qb->setParameter(2, $orgId);
        }

        $groups = $qb->getScalarResult();

        return $groups;
    }

    /**
     * Find a group by it's id
     * @param $id
     * @return Group
     */
    public function groupOfId($id)
    {
        return $this->em->getRepository($this->groupClass)
            ->find($id);
    }

    /**
     * @param Group $group
     * @return mixed
     */
    public function add(Group $group)
    {
        $this->em->persist($group);
        $this->em->flush();
    }

    /**
     * @param Group $group
     * @return mixed
     */
    public function update(Group $group)
    {
        $this->em->persist($group);
        $this->em->flush();
    }

    /**
     * @param Group $group
     * @return mixed
     */
    public function remove(Group $group)
    {
        // TODO: Implement remove() method.
    }
}