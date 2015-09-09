<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 9/09/15
 * Time: 11:21
 */

namespace Bakgat\Notos\Infrastructure\Repositories\ACL;


use Bakgat\Notos\Domain\Model\ACL\UserRolesRepository;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Identity\User;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManager;

class UserRolesDoctrineORMRepository implements UserRolesRepository
{

    /** @var  EntityManagerInterface */
    private $em;
    /** @var  string */
    private $class;
    /** @var string */
    private $roleClass;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->class = 'Bakgat\Notos\Domain\Model\ACL\UserRole';
        $this->roleClass = 'Bakgat\Notos\Domain\Model\ACL\Role';
    }


    /**
     * Gets all the roles associated with an user
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function rolesOfUser(User $user, Organization $organization)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('r')
            ->from($this->roleClass, 'r')
            ->join('r.user_roles', 'ur')
            ->where(
                $qb->expr()->eq('ur.user', '?1'),
                $qb->expr()->eq('ur.organization', '?2')
            )
            ->setParameter(1, $user->id())
            ->setParameter(2, $organization->id());


        return $qb->getQuery()->getResult();
    }
}