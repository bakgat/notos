<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 15/10/15
 * Time: 13:55
 */

namespace Bakgat\Notos\Domain\Model\Identity;


class UsernameIsUnique implements UsernameSpecification
{
    /** @var UserRepository $userRepo */
    private $userRepo;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepo = $userRepository;
    }

    /**
     * Check to see if the specification is satisfied
     *
     * @param Username $username
     * @return bool
     */
    public function isSatisfiedBy(Username $username)
    {
        if (!$this->userRepo->userOfUsername($username)) {
            return true;
        }
        return false;
    }
}