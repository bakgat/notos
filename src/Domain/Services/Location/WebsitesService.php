<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 16/09/15
 * Time: 11:24
 */

namespace Bakgat\Notos\Domain\Services\Location;


use Bakgat\Notos\Domain\Model\Location\WebsitesRepository;

class WebsitesService
{
    /** @var WebsitesRepository $websitesRepository */
    private $websitesRepository;

    public function __construct(WebsitesRepository $websitesRepository)
    {
        $this->websitesRepository = $websitesRepository;
    }

    /**
     * Returns all websites
     *
     * @return mixed
     */
    public function all() {
        return $this->websitesRepository->all();
    }

    public function websiteOfId($id)
    {
        return $this->websitesRepository->websiteofId($id);
    }

    public function full()
    {
        return $this->websitesRepository->full();
    }
}