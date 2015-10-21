<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/09/15
 * Time: 13:52
 */

namespace Bakgat\Notos\Infrastructure\Repositories\ACL;


use Bakgat\Notos\Domain\Model\ACL\RoleRepository;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\Cache;

class RoleDoctrineORMRepository implements RoleRepository
{

    /** @var  EntityManagerInterface */
    private $em;
    /** @var  string */
    private $class;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->class = 'Bakgat\Notos\Domain\Model\ACL\Role';
    }

    /**
     * Get the role by slug
     *
     * @param $slug
     * @return mixed
     * @throws RoleNotFoundException
     */
    public function get($slug)
    {
        $lowerslug = strtolower($slug);

        //create unique key for kind and uppercased name
        $key = md5('role.' . $lowerslug);
        //cache has value return this one
        $cached = Cache::get($key);
        if ($cached) {
            return $cached;
        }

        $role = $this->em->getRepository($this->class)
            ->findOneBy(['slug' => $lowerslug]);

        if (!$role) {
            throw new RoleNotFoundException($slug);
        }
        //cache it for next request
        Cache::forever($key, $role);
        return Cache::get($key);
    }

}