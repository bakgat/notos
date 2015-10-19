<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 14/10/15
 * Time: 07:21
 */

namespace Bakgat\Notos\Http\Controllers\Resource;


use Bakgat\Notos\Domain\Model\DomainException;

class OrganizationNotFoundException extends DomainException
{

    /**
     * OrganizationNotFoundException constructor.
     * @param $orgId
     */
    public function __construct($orgId)
    {
    }

}