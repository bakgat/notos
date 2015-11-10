<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 20/09/15
 * Time: 21:09
 */

namespace Bakgat\Notos\Domain\Services\Identity;


use Bakgat\Notos\Domain\Model\Identity\GroupRepository;
use Bakgat\Notos\Domain\Model\KindRepository;
use Doctrine\Common\Collections\ArrayCollection;

class GroupService
{
    /** @var KindRepository $kindRepo */
    private $kindRepo;
    /** @var GroupRepository $groupRepo */
    private $groupRepo;

    public function __construct(KindRepository $kindRepository, GroupRepository $groupRepository) {
        $this->kindRepo = $kindRepository;
        $this->groupRepo = $groupRepository;
    }

    /**
     * @param $kind_name
     * @return ArrayCollection
     */
    public function groupsOfKind($kind_name)
    {
        $level = $this->kindRepo->get($kind_name);
        return $this->groupRepo->groupsOfKind($level);
    }
}