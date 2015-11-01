<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/09/15
 * Time: 22:10
 */

namespace Bakgat\Notos\Seeds\Fixtures;


use Bakgat\Notos\Domain\Model\Curricula\CurriculumRepository;
use Bakgat\Notos\Domain\Model\Descriptive\Tag;
use Bakgat\Notos\Domain\Model\Descriptive\TagName;
use Bakgat\Notos\Domain\Model\Descriptive\TagRepository;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Location\URL;
use Bakgat\Notos\Domain\Model\Location\Website;
use Bakgat\Notos\Domain\Model\Location\WebsitesRepository;
use Bakgat\Notos\Infrastructure\Repositories\Curriculum\CurriculumDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Descriptive\TagDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Location\WebsitesDoctrineORMRepository;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class WebsiteFixtures implements FixtureInterface
{
    /** @var ObjectManager $manager */
    private $manager;
    /** @var TagRepository $tagRepo */
    private $tagRepo;
    /** @var WebsitesRepository $websiteRepo */
    private $websiteRepo;
    /** @var CurriculumRepository $currRepo */
    private $currRepo;

    private $tags = ['optellen', 'aftrekken', 'tafels', 'wegen', 'Sinterklaas'];

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->tagRepo = new TagDoctrineORMRepository($manager);
        $this->websiteRepo = new WebsitesDoctrineORMRepository($manager);
        $this->currRepo = new CurriculumDoctrineORMRepository($manager);

        $this->createTags();
        $this->createWebsites();
    }

    public function createTags()
    {
        foreach ($this->tags as $name) {
            $tag = Tag::register(new TagName($name));
            $this->tagRepo->add($tag);
        }
    }

    public function createWebsites()
    {
        $websites = [
            ['n'=>'Rekenmeeseter', 'u'=>'www.rekenmeester.be', 'o'=>['WIS G1','WIS G1.a'], 't'=>['optellen', 'aftrekken']],
            ['n'=>'Google', 'u'=>'www.google.be', 'o'=>['WIS G7','WIS G9','WIS G9.e'], 't'=>['optellen', 'Sinterklaas']],
        ];

        foreach ($websites as $awebsite) {
            $website = Website::register(new Name($awebsite['n']), new URL($awebsite['u']));

            foreach ($awebsite['t'] as $atag) {
                $tag = $this->tagRepo->tagOfName(new TagName($atag));
                $website->addTag($tag);
            }
            foreach ($awebsite['o'] as $aobjective) {
                $objective = $this->currRepo->objectiveOfCode($aobjective);
                $website->addObjective($objective);
            }
            $this->manager->persist($website);

            $this->websiteRepo->add($website);
        }

    }
}