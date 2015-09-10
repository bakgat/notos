<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/07/15
 * Time: 22:04
 */

namespace Bakgat\Notos\Domain\Model\ACL;


trait HasPermissionInheritance
{
    protected $cacheInherit;

    protected function getPermissionsInherited()
    {
        $rights = [];
        $permissions = $this->permissions;
        // ntfs permissions
        // more permissive wins
        $tmp = [];
        $letNtfs = function ($alias, $slug) use (&$tmp) {
            $ntfs[$alias] = array_diff($slug, [false]);
            if (sizeof($ntfs) > 0) {
                $tmp = array_replace_recursive($tmp, $ntfs);
            }
        };
        foreach ($permissions as $row) {
            // permissions without inherit ids
            if (is_null($row->inherit()) || !$row->inherit()) {
                // ntfs determination
                $letNtfs($row->name(), $row->slug());
                // merge permissions
                $rights = array_replace_recursive($rights, [$row->name() => $row->slug()], $tmp);
                continue;
            }
            // process inherit_id recursively
            $inherited = $this->getRecursiveInherit($row->inherit(), $row->slug());

            /*
                $criteria = Criteria::create()->where(Criteria::expr()->in("id", $ids);

                return $this->getComments()->matching($criteria);
             */
            $criteria = Criteria::create()->where(Criteria::expr()->in('name', $row->name()));
            $merge = $permissions->matching($criteria);
            $result = [];
            foreach ($merge as $m) {
                $result[] = [
                    'slug' => $m->slug(),
                    'name' => $m->name()
                ];
            }

            // fix for l5.1 and backward compatibility.
            // lists() method should return as an array.
            // $merge = $this->collectionAsArray($merge);
            // replace and merge permissions
            $rights = array_replace_recursive($rights, $inherited, $result);
            // make sure we don't unset if
            // inherited & slave permission
            // have same names
            if (key($inherited) != $row->name())
                unset($rights[$row->name()]);
        }
        return $rights;
    }

    protected function getInherit($inherit)
    {
        if ($cache = $this->hasCache($inherit->id())) {
            return $cache;
        }
        $criteria = Criteria::create()->where(Criteria::expr()->in('id', $inherit->id()));
        $query = $this->permissions->matching($criteria);
        return is_object($query) ? $this->setCache($query) : false;
    }

    protected function getRecursiveInherit($inherit, $permissions)
    {
        // avoid infinite loops,
        // save ids temporarily.
        $avoid[] = $inherit;
        // ntfs permissions
        // determine if ntfs is enabled
        // then more permissive wins
        $tmp = [];
        $letNtfs = function ($slug) use (&$tmp) {
            if (config('acl.ntfs', true)) {
                $ntfs = array_diff($slug, [false]);
                if (sizeof($ntfs) > 0) {
                    $tmp = array_replace($tmp, $ntfs);
                }
            }
        };
        // get from cache or sql.
        $inherit = $this->getInherit($inherit);
        if ($inherit->exists) {
            // ntfs determination
            $letNtfs($inherit->slug);
            // replace and merge initial permission
            $permissions = array_replace_recursive($inherit->slug, $permissions, $tmp);
            // follow along into deeper inherited permissions recursively
            while ($inherit && $inherit->inherit_id > 0 && !is_null($inherit->inherit_id)) {
                // get inherit permission from cache or sql.
                $inherit = $this->getInherit($inherit->inherit_id);
                // ntfs determination
                $letNtfs($inherit->slug);
                // replace and merge permissions
                $permissions = array_replace_recursive($inherit->slug, $permissions, $tmp);
                // avoid getting into infinite loop
                $avoid[] = $inherit->id;
                if (in_array($inherit->inherit_id, $avoid)) {
                    break;
                }
            };
            return [$inherit->name => $permissions];
        }
        return $permissions;
    }

    protected function hasCache($inherit_id)
    {
        if (isset($this->cacheInherit[$inherit_id])) {
            return $this->cacheInherit[$inherit_id];
        }
        return false;
    }

    protected function setCache($inherit)
    {
        return $this->cacheInherit[$inherit->getKey()] = $inherit;
    }
}