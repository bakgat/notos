<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 20/09/15
 * Time: 21:09
 */

namespace Bakgat\Notos\Domain\Services\Identity;


use Bakgat\Notos\Domain\Model\Identity\Exceptions\GroupNotFoundException;
use Bakgat\Notos\Domain\Model\Identity\Group;
use Bakgat\Notos\Domain\Model\Identity\GroupRepository;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use Bakgat\Notos\Domain\Model\KindRepository;
use Bakgat\Notos\Domain\Model\Relations\PartyRelationRepository;
use Bakgat\Notos\Exceptions\UnprocessableEntityException;
use Doctrine\Common\Collections\ArrayCollection;

class GroupService
{
    /** @var KindRepository $kindRepo */
    private $kindRepo;
    /** @var GroupRepository $groupRepo */
    private $groupRepo;
    /** @var PartyRelationRepository $relRepo */
    private $relRepo;
    /** @var OrganizationRepository $orgRepo */
    private $orgRepo;

    public function __construct(KindRepository $kindRepository, GroupRepository $groupRepository,
                                PartyRelationRepository $partyRelationRepository, OrganizationRepository $organizationRepository)
    {
        $this->kindRepo = $kindRepository;
        $this->groupRepo = $groupRepository;
        $this->relRepo = $partyRelationRepository;
        $this->orgRepo = $organizationRepository;
    }


    /**
     * @param $kind_name
     * @param integer|null $orgId
     * @return ArrayCollection
     * @throws OrganizationNotFoundException
     */
    public function groupsOfKind($kind_name, $orgId = null)
    {
        $level = $this->kindRepo->get($kind_name);
        if ($orgId) {
            $organization = $this->orgRepo->organizationOfId($orgId);
            if (!$organization) {
                throw new OrganizationNotFoundException($orgId);
            }
        }

        return $this->groupRepo->groupsOfKind($level, $orgId);
    }

    public function groupOfId($id)
    {
        $group = $this->groupRepo->groupOfId($id);
        return $group;
    }

    public function add($orgId, $data)
    {
        $organization = $this->orgRepo->organizationOfId($orgId);
        if (!$organization) {
            throw new OrganizationNotFoundException($orgId);
        }
        $name = $this->nameIsRequired($data);

        $group = Group::register($name);
        $this->setDescription($data, $group);
        $this->setKind($data, $group);
        //TODO: set avatar

        $this->groupRepo->add($group);
        $this->setParent($data, $group);

        return $group;
    }

    public function update($id, $data)
    {
        $group = $this->groupOfId($id);
        if (!$group) {
            throw new GroupNotFoundException($id);
        }

        $name = $this->nameIsRequired($data);
        $group->setName($name);
        $this->setDescription($data, $group);
        $this->setKind($data, $group);
        //TODO: set avatar

        $this->groupRepo->update($group);
        $this->setParent($data, $group);

        return $group;
    }


    /* ***************************************************
     * PRIVATE METHODS
     * **************************************************/
    /**
     * @param $data
     * @return mixed
     * @throws UnprocessableEntityException
     */
    private function nameIsRequired($data)
    {
        if (!isset($data['name'])) {
            throw new UnprocessableEntityException();
        }
        return new Name($data['name']);
    }

    /**
     * @param $data
     * @param $group
     */
    private function setParent($data, $group)
    {
        if (isset($data['parent']) && isset($data['parent']['id'])) {
            $parent = $this->groupOfId($data['parent']['id']);
            if ($parent) {
                $parent->addChild($group);
                $this->groupRepo->update($parent);
            }
        }
    }

    /**
     * @param $data
     * @param $group
     * @return mixed
     */
    private function setDescription($data, $group)
    {
        if (isset($data['description']))
            $group->setDescription($data['description']);
        return $data;
        return $data;
    }

    /**
     * @param $data
     * @param $group
     * @return mixed
     */
    private function setKind($data, $group)
    {
        if (isset($data['kind']) && isset($data['kind']['name'])) {
            $kind_name = $data['kind']['name'];
            $kind = $this->kindRepo->get($kind_name);
            if ($kind) {
                //TODO throw error when kind is not found
                $group->setKind($kind);
                return $data;
            }
            return $data;
        }
        return $data;
    }
}