<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 16/09/15
 * Time: 08:31
 */

namespace Bakgat\Notos\Domain\Model\Location;


interface WebsitesRepository
{
    /**
     * Returns all websites
     *
     * @return mixed
     */
    public function all();

    /**
     * Adds a new website
     *
     * @param Website $website
     * @return mixed
     */
    public function add(Website $website);

    /**
     * Updates an existing website
     *
     * @param Website $website
     * @return mixed
     */
    public function update(Website $website);

    /**
     * Find a website by it's id
     * @param $id
     * @return mixed
     */
    public function websiteofId($id);

    /**
     * Find a website by it's url
     *
     * @param URL $URL
     * @return mixed
     */
    public function websiteOfURL(URL $URL);

    /**
     * Get all websites, fully loaded with all relations
     *
     * @return mixed
     */
    public function full();
}