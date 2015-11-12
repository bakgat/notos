<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 16/09/15
 * Time: 11:24
 */

namespace Bakgat\Notos\Domain\Services\Location;


use Bakgat\Notos\Domain\Model\Curricula\CurriculumRepository;
use Bakgat\Notos\Domain\Model\Location\Exceptions\WebsiteNotFoundException;
use Bakgat\Notos\Domain\Model\Descriptive\TagRepository;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Location\URL;
use Bakgat\Notos\Domain\Model\Location\Website;
use Bakgat\Notos\Domain\Model\Location\WebsitesRepository;

class WebsitesService
{
    /** @var WebsitesRepository $websitesRepository */
    private $websitesRepository;
    /** @var TagRepository $tagRepository */
    private $tagRepository;
    /** @var CurriculumRepository $curriculumRepository */
    private $curriculumRepository;

    public function __construct(WebsitesRepository $websitesRepository, TagRepository $tagRepository, CurriculumRepository $curriculumRepository)
    {
        $this->websitesRepository = $websitesRepository;
        $this->tagRepository = $tagRepository;
        $this->curriculumRepository = $curriculumRepository;
    }

    /**
     * Returns all websites
     *
     * @return ArrayCollection
     */
    public function all()
    {
        $websites = $this->websitesRepository->all();
        return $websites;
    }

    /**
     * Find a website by it's id
     * @param $id
     * @return Website
     * @throws WebsiteNotFoundException
     */
    public function websiteOfId($id)
    {
        $website = $this->websitesRepository->websiteOfId($id);
        if(!$website) {
            throw new WebsiteNotFoundException($id);
        }
        return $website;
    }

    /**
     * Find a website by it's URL
     * @param URL $url
     * @return Website
     * @throws WebsiteNotFoundException
     */
    public function websiteOfURL(URL $url)
    {
        $website = $this->websitesRepository->websiteOfURL($url);
        if(!$website) {
            throw new WebsiteNotFoundException($url);
        }
        return $website;
    }

    /**
     * Returns a list of websites with fields loaded fully (JMS\Groups({"full"}))
     * @return mixed
     */
    public function full()
    {
        $websites = $this->websitesRepository->full();
        return $websites;
    }

    /**
     * Stores a website (with tags and objectives).
     * @param $data
     */
    public function add($data)
    {
        /** @var Website $website */
        $website = Website::register(new Name($data['name']), new URL($data['url']));
        if (isset($data['description'])) $website->setDescription($data['description']);

        //Sync tags
        $website->clearTags();
        if (isset($data['tags'])) {
            foreach ($data['tags'] as $tag) {
                $t = $this->tagRepository->tagOfNameOrCreate(new Name($tag['name']));
                $website->addTag($t);
            }
        }


        $website->clearObjectives();
        if (isset($data['objectives'])) {
            foreach ($data['objectives'] as $objective) {
                $o = $this->curriculumRepository->objectiveOfId($objective['id']);
                $website->addObjective($o);
            }
        }

        $this->websitesRepository->add($website);
    }

    /**
     * Updates a website (with tags and objectives) of a given id .
     * @param $data
     */
    public function update($id, $data)
    {
        /** @var Website $website */
        $website = $this->websiteOfId($id);

        if ($website) {
            $website->setName(new Name($data['name']));
            $website->setUrl(new URL($data['url']));
            if (isset($data['description'])) $website->setDescription($data['description']);

            //Sync tags
            $website->clearTags();
            if (isset($data['tags'])) {
                foreach ($data['tags'] as $tag) {
                    $t = $this->tagRepository->tagOfNameOrCreate(new Name($tag['name']));
                    $website->addTag($t);
                }
            }

            $website->clearObjectives();
            if (isset($data['objectives'])) {
                foreach ($data['objectives'] as $objective) {
                    $o = $this->curriculumRepository->objectiveOfId($objective['id']);
                    $website->addObjective($o);
                }
            }
            $this->websitesRepository->update($website);
        }
    }

}