<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 9/09/15
 * Time: 11:28
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories\Fixtures;


use Bakgat\Notos\Domain\Model\ACL\Role;
use Bakgat\Notos\Domain\Model\ACL\UserRole;
use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Username;
use Bakgat\Notos\Infrastructure\Repositories\OrganizationDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\UserDoctrineORMRepository;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Tests\ORM\Mapping\User;

class ACLFixtures implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $userRepo = new UserDoctrineORMRepository($manager);
        $orgRepo = new OrganizationDoctrineORMRepository($manager);

        $admin = Role::register('admin');
        $SAs = Role::register('sas');
        $manager->persist($admin);
        $manager->persist($SAs);

        $karl = $userRepo->userOfUsername(new Username('karl.vaniseghem@klimtoren.bez'));
        $klimtoren = $orgRepo->organizationOfDomain(new DomainName('klimtoren.bez'));
        $wassenaard = $orgRepo->organizationOfDomain(new DomainName('wassenaard.bez'));

        $karl_is_admin_klimtoren = UserRole::register($karl, $admin, $klimtoren);
        $karl_is_sa_klimtoren = UserRole::register($karl, $SAs, $klimtoren);
        $karl_is_sa_wassenaard = UserRole::register($karl, $SAs, $wassenaard);

        $manager->persist($karl_is_admin_klimtoren);
        $manager->persist($karl_is_sa_klimtoren);
        $manager->persist($karl_is_sa_wassenaard);

        $manager->flush();

    }
}