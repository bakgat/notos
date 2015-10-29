<?php

namespace Bakgat\Notos\Tests\Infrastructure\Repositories\Fixtures;

use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Email;
use Bakgat\Notos\Domain\Model\Identity\HashedPassword;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Identity\User;
use Bakgat\Notos\Domain\Model\Identity\Username;
use Bakgat\Notos\Domain\Model\Relations\PartyRelation;
use Bakgat\Notos\Infrastructure\Repositories\Identity\KindCacheRepository;
use Bakgat\Notos\Infrastructure\Repositories\Identity\OrganizationDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Identity\UserDoctrineORMRepository;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Illuminate\Support\Facades\Artisan;


class PartyRelationFixtures implements FixtureInterface
{


    /**
     * Load the User fixtures
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $kindRepo = new KindCacheRepository($manager);
        $userRepo = new UserDoctrineORMRepository($manager);
        $orgRepo = new OrganizationDoctrineORMRepository($manager);

        $user = $userRepo->userOfUsername(new Username('ulrike.drieskens@gmail.com'));
        $org = $orgRepo->organizationOfDomain(new DomainName('klimtoren.bez'));
        $ulrike_emp = PartyRelation::register($user, $org, $kindRepo->get('employee'));
        $manager->persist($ulrike_emp);

        $ulrike_user = PartyRelation::register($user, $org, $kindRepo->get('user'));
        $manager->persist($ulrike_user);


        $user = $userRepo->userOfUsername(new Username('karl.vaniseghem@klimtoren.bez'));
        $karl_emp = PartyRelation::register($user, $org, $kindRepo->get('employee'));
        $manager->persist($karl_emp);

        $karl_user = PartyRelation::register($user, $org, $kindRepo->get('user'));
        $manager->persist($karl_user);

        $manager->flush();
    }


}