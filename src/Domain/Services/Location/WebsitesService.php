<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 16/09/15
 * Time: 11:24
 */

namespace Bakgat\Notos\Domain\Services\Location;


use Bakgat\Notos\Domain\Model\Curricula\CurriculumRepository;
use Bakgat\Notos\Domain\Model\Descriptive\TagName;
use Bakgat\Notos\Domain\Model\Location\Exceptions\WebsiteNotFoundException;
use Bakgat\Notos\Domain\Model\Descriptive\TagRepository;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Location\URL;
use Bakgat\Notos\Domain\Model\Location\Website;
use Bakgat\Notos\Domain\Model\Location\WebsitesRepository;
use Bakgat\Notos\Domain\Model\Resource\AssetRepository;
use Bakgat\Notos\Domain\Services\Resource\AssetsManager;
use Bakgat\Notos\Exceptions\UnprocessableEntityException;

class WebsitesService
{
    /** @var WebsitesRepository $websitesRepository */
    private $websitesRepository;
    /** @var TagRepository $tagRepository */
    private $tagRepository;
    /** @var CurriculumRepository $curriculumRepository */
    private $curriculumRepository;
    /** @var AssetRepository $assetsRepo */
    private $assetsRepo;

    public function __construct(WebsitesRepository $websitesRepository,
                                TagRepository $tagRepository,
                                CurriculumRepository $curriculumRepository,
                                AssetRepository $assetRepository)
    {
        $this->websitesRepository = $websitesRepository;
        $this->tagRepository = $tagRepository;
        $this->curriculumRepository = $curriculumRepository;
        $this->assetsRepo = $assetRepository;
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
        if (!$website) {
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
        if (!$website) {
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
     * @return Website
     */
    public function add($data)
    {
        //MANDATORY FIELDS ---------------------------------
        //TODO: how to collect errors in bag
        //and throw bag when done

        $this->nameIsRequired($data);
        $this->urlIsRequired($data);

        /** @var Website $website */
        $website = Website::register(new Name($data['name']), new URL($data['url']));
        if (isset($data['description'])) {
            $website->setDescription($data['description']);
        }

        //ADD IMAGE ---------------------)-------------------
        if (isset($data['image']) && isset($data['image']['guid'])) {
            $image = $this->assetsRepo->assetOfGuid($data['image']['guid']);
            $website->setImage($image);
        }

        //SYNC TAGS -----------------------------------------
        $website->clearTags();
        if (isset($data['tags'])) {
            foreach ($data['tags'] as $tag) {
                $tagName = new TagName($tag['name']);
                //TODO what if tags are invalid
                //fail or collect errors / log error???
                $t = $this->tagRepository->tagOfNameOrCreate($tagName);
                $website->addTag($t);
            }
        }

        //SYNC OBJECTIVES -----------------------------------
        $website->clearObjectives();
        if (isset($data['objectives'])) {
            foreach ($data['objectives'] as $objective) {
                $o = $this->curriculumRepository->objectiveOfId($objective['id']);
                //TODO what if objectiveOfId does return null ???
                //Fail or log error???
                $website->addObjective($o);
            }
        }

        $this->websitesRepository->add($website);
        return $website;
    }

    /**
     * Updates a website (with tags and objectives) of a given id .
     * @param $id
     * @param $data
     * @return Website
     * @throws WebsiteNotFoundException
     */
    public function update($id, $data)
    {
        //MANDATORY FIELDS ---------------------------------
        $this->nameIsRequired($data);
        $this->urlIsRequired($data);


        /** @var Website $website */
        $website = $this->websiteOfId($id);

        if (!$website) {
            throw new WebsiteNotFoundException($id);
        }
        $website->setName(new Name($data['name']));
        $website->setUrl(new URL($data['url']));
        if (isset($data['description'])) $website->setDescription($data['description']);

        //ADD IMAGE ---------------------)-------------------
        if (isset($data['image']) && isset($data['image']['guid'])) {
            $image = $this->assetsRepo->assetOfGuid($data['image']['guid']);
            $website->setImage($image);
        }

        //SYNC TAGS -----------------------------------------
        $website->clearTags();
        if (isset($data['tags'])) {
            foreach ($data['tags'] as $tag) {
                $t = $this->tagRepository->tagOfNameOrCreate(new TagName($tag['name']));
                $website->addTag($t);
            }
        }

        //SYNC OBJECTIVES -----------------------------------
        $website->clearObjectives();
        if (isset($data['objectives'])) {
            foreach ($data['objectives'] as $objective) {
                $o = $this->curriculumRepository->objectiveOfId($objective['id']);
                $website->addObjective($o);
            }
        }
        $this->websitesRepository->update($website);
        return $website;

    }

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
    }

    private function urlIsRequired($data)
    {
        if (!isset($data['url'])) {
            throw new UnprocessableEntityException();
        }
    }

}