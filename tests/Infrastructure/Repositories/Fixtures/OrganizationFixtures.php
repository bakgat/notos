<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 22/06/15
 * Time: 15:56
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories\Fixtures;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Infrastructure\Repositories\KindCacheRepository;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class OrganizationFixtures implements  FixtureInterface {

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $kindRepo = new KindCacheRepository($manager);

        $name = new Name('VBS De Klimtoren');
        $domainname = new DomainName('klimtoren.bez');
        $avatar = '/img/org.png';

        $org = Organization::register($name);
        $org->setDomainName($domainname);
        $org->setAvatar($avatar);
        $org->setKind($kindRepo->get('school'));

        $manager->persist($org);

        $varsenare = Organization::register(new Name('VBS De Wassenaard'));
        $varsenare->setDomainName(new DomainName('wassenaard.bez'));

        $manager->persist($varsenare);

        $manager->flush();
    }
}