<?php

namespace Bakgat\Notos\Tests\Infrastructure\Repositories\Fixtures;

use Bakgat\Notos\Domain\Model\Identity\Email;
use Bakgat\Notos\Domain\Model\Identity\Gender;
use Bakgat\Notos\Domain\Model\Identity\HashedPassword;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\User;
use Bakgat\Notos\Domain\Model\Identity\Username;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class UserFixtures implements FixtureInterface
{
    /**
     * Load the User fixtures
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $firstName = new Name('Karl');
        $lastName = new Name('Van Iseghem');
        $username = new Username('karl.vaniseghem@klimtoren.bez');
        $password = new HashedPassword(md5('password'));
        $email = new Email('karl.vaniseghem@klimtoren.bez');
        $gender = new Gender('M');
        $user = User::register($firstName, $lastName, $username, $password, $email, $gender);
        $manager->persist($user);

        $firstName = new Name('Ulrike');
        $lastName = new Name('Drieskens');
        $username = new Username('ulrike.drieskens@gmail.com');
        $password = new HashedPassword(md5('password'));
        $email = new Email('ulrike.drieskens@gmail.com');
        $gender = new Gender('F');
        $user = User::register($firstName, $lastName, $username, $password, $email, $gender);
        $manager->persist($user);

        $manager->flush();
    }
}