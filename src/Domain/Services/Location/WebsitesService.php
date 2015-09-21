<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 16/09/15
 * Time: 11:24
 */

namespace Bakgat\Notos\Domain\Services\Location;


use Bakgat\Notos\Domain\Model\Curricula\CurriculumRepository;
use Bakgat\Notos\Domain\Model\Descriptive\Tag;
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
     * @return mixed
     */
    public function all()
    {
        return $this->websitesRepository->all();
    }

    /**
     * Find a website by it's id
     * @param $id
     * @return mixed
     */
    public function websiteOfId($id)
    {
        return $this->websitesRepository->websiteofId($id);
    }

    /**
     * Returns a list of websites with fields loaded fully (JMS\Groups({"full"}))
     * @return mixed
     */
    public function full()
    {
        return $this->websitesRepository->full();
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
        foreach ($data['tags'] as $tag) {
            $t = $this->tagRepository->tagOfNameOrCreate(new Name($tag['name']));
            $website->addTag($t);
        }

        $website->clearObjectives();
        foreach ($data['objectives'] as $objective) {
            $o = $this->curriculumRepository->objectiveOfId($objective['id']);
            $website->addObjective($o);
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
            foreach ($data['tags'] as $tag) {
                $t = $this->tagRepository->tagOfNameOrCreate(new Name($tag['name']));
                $website->addTag($t);
            }

            $website->clearObjectives();
            foreach ($data['objectives'] as $objective) {
                $o = $this->curriculumRepository->objectiveOfId($objective['id']);
                $website->addObjective($o);
            }

            $this->websitesRepository->update($website);
        }
    }

}