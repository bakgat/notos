<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 10/11/15
 * Time: 16:03
 */

namespace Bakgat\Notos\Tests\Domain\Services;


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

trait TestDataTrait
{
    /** @var User $karl */
    private $karl;
    /** @var Organization $klimtoren */
    private $klimtoren;
    /** @var Role $roleUser */
    private $roleUser;
    /** @var array $userData */
    private $userData;

    protected function setupTestData() {
        //USER
        $fname = new Name('Karl');
        $lname = new Name('Van Iseghem');
        $uname = new Username('karl.vaniseghem@klimtoren.be');
        $pwd = new HashedPassword(bcrypt('password'));
        $gender = new Gender(Gender::MALE);
        $email = new Email($uname->toString());

        $this->karl = User::register($fname, $lname, $uname, $pwd, $email, $gender);

        //ORGANIZATION
        $orgName = new Name('VBS De Klimtoren');
        $domainName = new DomainName('klimtoren.be');

        $this->klimtoren = Organization::register($orgName, $domainName);

        //ACL
        $role_admin = Role::register('admin');
        $role_sa = Role::register('sa');
        $this->roleUser = Role::register('user');
        UserRole::register($this->karl, $role_admin, $this->klimtoren);
        UserRole::register($this->karl, $role_sa, $this->klimtoren);

        $this->userData = [
            'first_name' => 'Karl',
            'last_name' => 'Van Iseghem',
            'username' => 'karl.vaniseghem@klimtoren.be',
            'password' => 'password',
            'gender' => 'M'
        ];
    }
}