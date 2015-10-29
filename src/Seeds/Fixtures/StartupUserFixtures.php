<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 20/09/15
 * Time: 14:29
 */

namespace Bakgat\Notos\Seeds\Fixtures;


use Bakgat\Notos\Domain\Model\ACL\Role;
use Bakgat\Notos\Domain\Model\ACL\UserRole;
use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Email;
use Bakgat\Notos\Domain\Model\Identity\Gender;
use Bakgat\Notos\Domain\Model\Identity\HashedPassword;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Identity\User;
use Bakgat\Notos\Domain\Model\Identity\Username;
use Bakgat\Notos\Domain\Model\Relations\PartyRelation;
use Bakgat\Notos\Infrastructure\Repositories\Identity\KindCacheRepository;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class StartupUserFixtures implements FixtureInterface
{
    /** @var KindCacheRepository $kindRepo */
    private $kindRepo;

    /** @var ObjectManager $manager */
    private $manager;

    /** @var Organization $klimtoren */
    private $klimtoren;
    /** @var Organization $wassenaard */
    private $wassenaard;
    /** @var Organization $loopbrug */
    private $loopbrug;
    /** @var Organization $boompje */
    private $boompje;

    /** @var Role $sa */
    private $sa;
    /** @var Role $admin */
    private $admin;
    /** @var Role $user_admin */
    private $user_admin;
    /** @var Role $website_moderator */
    private $website_moderator;
    /** @var Role $book_moderator */
    private $book_moderator;

    /** @var Role $user_role */
    private $user_role;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $this->kindRepo = new KindCacheRepository($manager);

        $this->createRoles();
        $this->createOrganizations();
        $this->createUsers();
        $this->manager->flush();
    }

    public function createRoles()
    {
        $this->sa = Role::register('sa');
        $this->user_role = Role::register('user');
        $this->admin = Role::register('admin');
        $this->user_admin = Role::register('user_admin');
        $this->website_moderator = Role::register('website_moderator');
        $this->book_moderator = Role::register('book_moderator');

        $this->manager->persist($this->sa);
        $this->manager->persist($this->user_role);
        $this->manager->persist($this->admin);
        $this->manager->persist($this->user_admin);
        $this->manager->persist($this->website_moderator);
        $this->manager->persist($this->book_moderator);

        $this->manager->flush();
    }

    public function createOrganizations()
    {
        $this->klimtoren = Organization::register(new Name('VBS De Klimtoren'), new DomainName('klimtoren.be'));
        $this->wassenaard = Organization::register(new Name('VBS De Wassenaard'), new DomainName('wassenaard.be'));
        $this->loopbrug = Organization::register(new Name('VBS De Loopbrug'), new DomainName('deloopbrug.be'));
        $this->boompje = Organization::register(new Name('VBS \'t Boompje'), new DomainName('tboompje.be'));

        $this->manager->persist($this->klimtoren);
        $this->manager->persist($this->wassenaard);
        $this->manager->persist($this->loopbrug);
        $this->manager->persist($this->boompje);

        $this->manager->flush();
    }

    public function createUsers()
    {
        $male = new Gender('m');
        $female = new Gender('f');

        $users = [
            ['fn' => 'Karl', 'ln' => 'Van Iseghem', 'un' => 'karl.vaniseghem@klimtoren.be', 'pwd' => 'password', 'realms' => [$this->klimtoren, $this->wassenaard, $this->boompje, $this->loopbrug], 'roles' => [$this->sa]],
            ['fn' => 'Bart', 'ln' => 'Verfaillie', 'un' => 'bart.verfaillie@dewassenaard.be', 'pwd' => 'password', 'realms' => [$this->wassenaard], 'roles' => [$this->website_moderator]],
            ['fn' => 'Lut', 'ln' => 'Ghyoot', 'un' => 'lut.ghyoot@dewassenaard.be', 'pwd' => 'password', 'realms' => [$this->wassenaard], 'roles' => [$this->website_moderator]],
            ['fn' => 'Geertrui', 'ln' => 'Deprijcker', 'un' => 'geertrui.deprijcker@deloopbrug.be', 'pwd' => 'password', 'realms' => [$this->loopbrug], 'roles' => [$this->website_moderator]],
            ['fn' => 'Myriam', 'ln' => 'Monstrey', 'un' => 'myriam.monstrey@deloopbrug.be', 'pwd' => 'password', 'realms' => [$this->loopbrug], 'roles' => [$this->website_moderator]],
            ['fn' => 'Annemie', 'ln' => 'Demaré', 'un' => 'annemie.demare@tboompje.be', 'pwd' => 'password', 'realms' => [$this->boompje], 'roles' => [$this->website_moderator]],
        ];


        $emp = $this->kindRepo->get('employee');

        foreach ($users as $arr_user) {
            //CREATE USER
            $firstName = new Name($arr_user['fn']);
            $lastName = new Name($arr_user['ln']);
            $userName = new Username($arr_user['un']);
            $pwd = new HashedPassword(bcrypt($arr_user['pwd']));
            $reset_email = new Email($userName->toString());

            $user = User::register($firstName, $lastName, $userName, $pwd, $reset_email, $male);
            $this->manager->persist($user);


            //SET RELATION
            $rel = PartyRelation::register($user, $arr_user['realms'][0], $emp);
            $this->manager->merge($rel);

            //Set Roles
            foreach ($arr_user['roles'] as $role) {
                $ur = UserRole::register($user, $role, $arr_user['realms'][0]);
                $this->manager->persist($ur);
            }

            foreach ($arr_user['realms'] as $realm) {
                $ur = UserRole::register($user, $this->user_role, $realm);
                $this->manager->persist($ur);
            }
        }
        $this->manager->flush();
    }
}