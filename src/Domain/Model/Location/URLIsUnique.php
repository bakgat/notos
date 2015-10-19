<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 15/10/15
 * Time: 11:44
 */

namespace Bakgat\Notos\Domain\Model\Location;


class URLIsUnique implements URLSpecification
{

    /** @var WebsitesRepository $websiteRepo */
    private $websiteRepo;

    /**
     * @param WebsitesRepository $websitesRepository
     */
    public function __construct(WebsitesRepository $websitesRepository)
    {
        $this->websiteRepo = $websitesRepository;
    }

    /**
     * Check to see if the specification is satisfied
     *
     * @param URL $url
     * @return bool
     */
    public function isSatisfiedBy(URL $url)
    {
        if (!$this->websiteRepo->websiteOfURL($url)) {
            return true;
        }
        return false;
    }
}