<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 18/06/15
 * Time: 09:09
 */

namespace Bakgat\Notos\Infrastructure\Repositories;


use Bakgat\Notos\Domain\Model\Identity\Email;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Identity\PartyRepository;
use Bakgat\Notos\Domain\Model\Identity\User;
use Bakgat\Notos\Domain\Model\Identity\Username;
use Bakgat\Notos\Domain\Model\Identity\UserRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

class UserDoctrineORMRepository implements UserRepository
{

    /** @var EntityManagerInterface */
    private $em;
    /** @var string */
    private $class;
    /** @var string */
    private $relClass;
    /** @var string */
    private $orgClass;
    /** @var string */
    private $urClass;
    /** @var  @var string */
    private $kindClass;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->class = 'Bakgat\Notos\Domain\Model\Identity\User';
        $this->orgClass = 'Bakgat\Notos\Domain\Model\Identity\Organization';
        $this->relClass = 'Bakgat\Notos\Domain\Model\Relations\PartyRelation';
        $this->urClass = 'Bakgat\Notos\Domain\Model\ACL\UserRole';
    }

    /**
     * Returns all parties
     *
     * @param Organization $organization
     * @return ArrayCollection
     */
    public function all(Organization $organization)
    {
        //TODO where user has role USER in organization that's alive

        $qb = $this->em->createQueryBuilder();
        $qb->select('u.id, u.firstName, u.lastName, u.username')
            ->from($this->class, 'u')
            ->join('u.relatedTo', 'pr')
            //->join('pr.kind', 'k')
            ->where(
                $qb->expr()->eq('pr.reference', '?1')
            // $qb->expr()->eq('k.name', '?2')
            )
            ->setParameter(1, $organization->id())
            //->setParameter(2, 'USER')
            ->groupBy('u.id');

        return $qb->getQuery()->getResult();
    }


    /**
     * Adds a new User
     *
     * @param User $user
     * @return void
     */
    public function add(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * Updates an existing User
     *
     * @param User $user
     * @return void
     */
    public function update(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * Find a user by their id
     *
     * @param $id
     * @return User
     */
    public function userOfId($id)
    {
        return $this->em->getRepository($this->class)->findOneBy(['id' => $id]);
    }

    /**
     * Find a user by their email address
     *
     * @param $email
     * @return User
     */
    public function userOfEmail(Email $email)
    {
        return null;
    }

    /**
     * Find a user by their username
     *
     * @param Username $username
     * @return User
     */
    public function userOfUsername(Username $username)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('u, p, pi')
            ->from($this->class, 'u')
            ->where(
                $qb->expr()->eq('u.username', '?1')
            )
            ->setParameter(1, $username->toString());
        return $qb->getQuery()->getSingleResult();
    }

    /**
     * Find a user by their username and load all ACL along
     * @param Username $username
     * @param Organization $organization
     * @return mixed
     */
    public function userWithACL(Username $username, Organization $organization)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('u, ur, r')
            ->from($this->class, 'u')
            ->join('u.user_roles', 'ur')
            ->join('ur.role', 'r')
            ->where(
                $qb->expr()->eq('u.username', '?1'),
                $qb->expr()->eq('ur.organization', '?2')
            )
            ->setParameter(1, $username->toString())
            ->setParameter(2, $organization->id());

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * Finds the organizations in which the given user is registered.
     *
     * @param User $user
     * @return ArrayCollection
     */
    public function organizationsOfUser(User $user)
    {
        //TODO where user has role USER in organization that's alive
        $qb = $this->em->createQueryBuilder();
        $qb->select('ur, o')
            ->from($this->urClass, 'ur')
            ->join('ur.organization', 'o')
            ->join('ur.role', 'r')
            ->where(
                $qb->expr()->eq('ur.user', '?1'),
                $qb->expr()->eq('r.slug', '?2')
            )
            ->setParameter(1, $user->id())
            ->setParameter(2, 'user');

        $userRoles = $qb->getQuery()->getResult();

        //convert to only organizations
        $result = [];
        foreach ($userRoles as $userRole) {
            if (!in_array($userRole->organization(), $result)) {
                $result[] = $userRole->organization();
            }
        }

        return $result;

    }
}