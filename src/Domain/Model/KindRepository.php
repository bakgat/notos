<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 26/06/15
 * Time: 11:12
 */

namespace Bakgat\Notos\Domain\Model;


interface KindRepository {
    /**
     * Get the kind by name
     *
     * @param $name
     * @return Kind
     */
    public function get($name);
}