<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 8/07/15
 * Time: 11:54
 */

namespace Bakgat\Notos\Domain\Model\ACL;


trait Helper
{
    /* ***************************************************
     * Protected methods
     * **************************************************/
    protected function parseOperator($str)
    {
        // if its an array lets use
        // and operator by default
        if (is_array($str)) {
            $str = implode(',', $str);
        }
        if (preg_match('/([,|])(?:\s+)?/', $str, $m)) {
            return $m[1] == '|' ? 'or' : 'and';
        }
        return false;
    }

    /**
     * Converts strings having comma
     * or pipe to an array in
     * lowercase
     *
     * @param $str
     * @return array
     */
    protected function hasDelimiterToArray($str)
    {
        if (is_string($str) && preg_match('/[,|]/is', $str)) {
            return preg_split('/ ?[,|] ?/', strtolower($str));
        }
        return is_array($str) ?
            array_filter($str, 'strtolower') : is_object($str) ?
                $str : strtolower($str);
    }

    /* ***************************************************
     * Slug permission related protected methods
     * **************************************************/
    protected function toDotPermissions(array $permissions)
    {
        $data = [];
        //$permissions = $this->permissions->lists('slug', 'name');
        foreach ($permissions as $alias => $perm) {
            if (!is_array($perm)) continue;
            foreach ($perm as $key => $value) {
                //if ( (bool) $value == false ) continue;
                $slug = $key . '.' . $alias;
                $data[$slug] = $value;
                //$data[] = $slug;
            }
        }
        return $data;
    }


}