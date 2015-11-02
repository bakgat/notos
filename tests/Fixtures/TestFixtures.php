<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 23/10/15
 * Time: 15:13
 */

namespace Bakgat\Notos\Tests\Fixtures;


use Bakgat\Notos\Domain\Model\ACL\Role;
use Bakgat\Notos\Domain\Model\ACL\UserRole;
use Bakgat\Notos\Domain\Model\Curricula\Course;
use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Email;
use Bakgat\Notos\Domain\Model\Identity\Gender;
use Bakgat\Notos\Domain\Model\Identity\Group;
use Bakgat\Notos\Domain\Model\Identity\HashedPassword;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Identity\User;
use Bakgat\Notos\Domain\Model\Identity\Username;
use Bakgat\Notos\Domain\Model\Kind;
use Bakgat\Notos\Domain\Model\Location\URL;
use Bakgat\Notos\Domain\Model\Location\Website;
use Bakgat\Notos\Domain\Model\Relations\PartyRelation;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TestFixtures implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /* ***************************************************
         * USERS
         * **************************************************/
        $fn_karl = new Name('Karl');
        $ln_karl = new Name('Van Iseghem');
        $un_karl = new Username('karl.vaniseghem@klimtoren.be');
        $e_karl = new Email($un_karl->toString());
        $pwd_karl = new HashedPassword(bcrypt('password'));
        $g_karl = new Gender(Gender::MALE);

        $karl = User::register($fn_karl, $ln_karl, $un_karl, $pwd_karl, $e_karl, $g_karl);

        $fn_reb = new Name('Rebekka');
        $ln_reb = new Name('Buyse');
        $un_reb = new Username('rebekka.buyse@klimtoren.be');
        $e_reb = new Email($un_reb->toString());
        $pwd_reb = new HashedPassword(bcrypt('password'));
        $g_reb = new Gender(Gender::FEMALE);

        $rebekka = User::register($fn_reb, $ln_reb, $un_reb, $pwd_reb, $e_reb, $g_reb);

        $manager->persist($karl);
        $manager->persist($rebekka);

        /* ***************************************************
         * ORGANIZATIONS
         * **************************************************/
        $n_klimtoren = new Name('VBS De Klimtoren');
        $dn_klimtoren = new DomainName('klimtoren.be');
        $klimtoren = Organization::register($n_klimtoren, $dn_klimtoren);

        $n_wassenaard = new Name('VBS De Wassenaard');
        $dn_wassenaard = new DomainName('wassenaard.be');
        $wassenaard = Organization::register($n_wassenaard, $dn_wassenaard);

        $manager->persist($klimtoren);
        $manager->persist($wassenaard);

        /* ***************************************************
         * ROLES
         * **************************************************/
        $user_role = new Role('user');
        $admin_role = new Role('admin');
        $sa_role = new Role('sa');
        $book_manager_role = new Role('book_manager');
        $website_manager_role = new Role('website_manager');

        $manager->persist($user_role);
        $manager->persist($admin_role);
        $manager->persist($sa_role);
        $manager->persist($book_manager_role);
        $manager->persist($website_manager_role);

        /* ***************************************************
         * KINDS
         * **************************************************/
        $k_employee = new Kind('employee');
        $k_level = new Kind('level');
        $k_classgroup = new Kind('classgroup');

        $manager->persist($k_employee);
        $manager->persist($k_level);
        $manager->persist($k_classgroup);


        /* ***************************************************
         * USERROLES
         * **************************************************/
        $karl_user_klimtoren = UserRole::register($karl, $user_role, $klimtoren);
        $rebekka_user_klimtoren = UserRole::register($rebekka, $user_role, $klimtoren);
        $karl_user_wassenaard = UserRole::register($karl, $user_role, $wassenaard);

        $manager->persist($karl_user_klimtoren);
        $manager->persist($rebekka_user_klimtoren);
        $manager->persist($karl_user_wassenaard);


        $karl_sa_klimtoren = UserRole::register($karl, $sa_role, $klimtoren);
        $karl_website_manager_wassenaard = UserRole::register($karl, $website_manager_role, $wassenaard);
        $rebekka_book_manager_klimtoren = UserRole::register($rebekka, $book_manager_role, $klimtoren);
        $rebekka_admin_klimtoren = UserRole::register($rebekka, $admin_role, $klimtoren);

        $manager->persist($karl_sa_klimtoren);
        $manager->persist($karl_website_manager_wassenaard);
        $manager->persist($rebekka_book_manager_klimtoren);
        $manager->persist($rebekka_admin_klimtoren);

        /* ***************************************************
         * GROUPS
         * **************************************************/
        $level_jkl = Group::register(new Name('JK'));
        $level_jkl->setKind($k_level);
        $level_okl = Group::register(new Name('OK'));
        $level_okl->setKind($k_level);
        $level_l1 = Group::register(new Name('L1'));
        $level_l1->setKind($k_level);

        $cg_K1 = Group::register(new Name('1KA'));
        $cg_K1->setKind($k_classgroup);

        $manager->persist($level_jkl);
        $manager->persist($level_okl);
        $manager->persist($level_l1);

        $manager->persist($cg_K1);

        /* ***************************************************
         * COURSES
         * **************************************************/
        $n_maths = new Name('wiskunde');
        $maths = Course::register($n_maths);

        $n_gk = new Name('getallenkennis');
        $gk = Course::register($n_gk);

        $n_lang = new Name('nederlands');
        $lang = Course::register($n_lang);

        $n_sp = new Name('spelling');
        $sp = Course::register($n_sp);

        $manager->persist($maths);
        $manager->persist($gk);
        $manager->persist($lang);
        $manager->persist($sp);

        /* ***************************************************
         * WEBSITES
         * **************************************************/
        $sites = [
            ['name' => 'Google', 'url' => 'www.google.be'],
            ['name' => 'VBS De Klimtoren', 'url' => 'www.klimtoren.be'],
            ['name' => 'Apple', 'url' => 'www.apple.com/benl'],
            ['name' => 'De Wassenaard', 'url' => 'www.wassenaard.be'],
            ['name' => 'Rekenmeester', 'url' => 'www.rekenmeester.be'],
            ['name' => 'C.A.R.E', 'url' => 'http://care-india.be/'],
            ['name' => 'VBS Ichtegem', 'url' => 'www.vbsichtegem.be'],
            ['name' => 'Replay', 'url' => 'http://www.replaycoverband.be'],
            ['name' => 'HP', 'url' => 'http://www.hp.com'],
            ['name' => 'Microsoft', 'url' => 'http://www.microsoft.com/'],
        ];


        foreach ($sites as $site) {
            $n_s = new Name($site['name']);
            $u_s = new URL($site['url']);
            $s = Website::register($n_s, $u_s);
            $manager->persist($s);
        }


        $manager->flush();
    }
}