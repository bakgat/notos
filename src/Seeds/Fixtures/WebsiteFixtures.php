<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/09/15
 * Time: 22:10
 */

namespace Bakgat\Notos\Seeds\Fixtures;


use Bakgat\Notos\Domain\Model\Descriptive\Tag;
use Bakgat\Notos\Domain\Model\Descriptive\TagRepository;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Location\URL;
use Bakgat\Notos\Domain\Model\Location\Website;
use Bakgat\Notos\Domain\Model\Location\WebsitesRepository;
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

        $this->createTags();
        $this->createWebsites();
    }

    public function createTags()
    {
        foreach ($this->tags as $name) {
            $tag = Tag::register(new Name($name));
            $this->tagRepo->add($tag);
        }
    }

    public function createWebsites()
    {
        $websites = [
            'Rekenmeester' => 'www.rekenmeester.be',
            'De Klimtoren' => 'www.klimtoren.be',
            'Google' => 'www.google.be',
        ];

        foreach ($websites as $name => $url) {
            $website = Website::register(new Name($name), new URL($url));
            $tname = $this->tags[rand(0, 4)];
            $tag = $this->tagRepo->tagOfName(new Name($tname));
            $website->addTag($tag);

            $this->manager->persist($website);


            $this->websiteRepo->add($website);
        }

    }
}