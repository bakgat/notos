<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 15/09/15
 * Time: 23:04
 */

namespace Bakgat\Notos\Infrastructure\Repositories;


use Bakgat\Notos\Domain\Model\Identity\Group;
use Bakgat\Notos\Domain\Model\Identity\GroupRepository;
use Doctrine\ORM\EntityManager;
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
}