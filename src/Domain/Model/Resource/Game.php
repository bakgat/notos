<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 27/06/15
 * Time: 11:31
 */

namespace Bakgat\Notos\Domain\Model\Resource;

use Bakgat\Notos\Domain\Model\Descriptive\Tag;
use Bakgat\Notos\Domain\Model\Identity\Isbn;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Identity\Party;
use Bakgat\Notos\Domain\RecordEvents;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

use Illuminate\Support\Arr;
use JMS\Serializer\Annotation as JMS;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="games")
 *
 */
class Game extends Resource
{

    /**
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $description;
    /**
     * @ORM\ManyToOne(targetEntity="Bakgat\Notos\Domain\Model\Resource\Asset")
     */
    private $image;
    /**
     * @ORM\Column(type="smallint",nullable=true)
     */
    private $min_age;
    /**
     * @ORM\Column(type="smallint",nullable=true)
     */
    private $max_age;
    /**
     * @ORM\Column(type="smallint",nullable=true)
     */
    private $min_number_players;
    /**
     * @ORM\Column(type="smallint",nullable=true)
     */
    private $max_number_players;

    /**
     * @ORM\ManyToMany(targetEntity="Bakgat\Notos\Domain\Model\Identity\Party")
     * @ORM\JoinTable(name="game_publishers",
     *      joinColumns={@ORM\JoinColumn(name="game_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="publisher_id", referencedColumnName="id")}
     * )
     *
     */
    private $publishers;
    /**
     * @ORM\ManyToMany(targetEntity="Bakgat\Notos\Domain\Model\Descriptive\Tag")
     * @ORM\JoinTable(name="game_tags",
     *      joinColumns={@ORM\JoinColumn(name="game_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      ))
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="Bakgat\Notos\Domain\Model\Identity\Organization")
     * @JMS\Exclude
     */
    private $organization;

    /**
     * @ORM\Column(type="smallint")
     */
    private $year_published;

    /**
     * @ORM\Column(type="string")
     */
    private $website;


    public function __construct(Name $name)
    {
        parent::__construct($name);

        $this->publishers = new ArrayCollection;
        $this->tags = new ArrayCollection;

    }

    public static function register(Name $name)
    {
        return new Game($name);
    }

    /**
     * @param Description description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function description()
    {
        return $this->description;
    }

    /**
     * @param Asset image
     * @return void
     */
    public function setImage(Asset $image)
    {
        $this->image = $image;
    }

    /**
     * @return Asset
     */
    public function image()
    {
        return $this->image;
    }

    /**
     * Destroys a Game. (Soft delete)
     */
    public function destroy()
    {
        $this->setDeletedAt(new DateTime);
    }

    /**
     * Adds a publisher for this game.
     *
     * @param Party $publisher
     */
    public function addPublisher(Party $publisher)
    {
        $this->publishers[] = $publisher;
    }

    /**
     * Returns the publishers of this game.
     *
     * @return ArrayCollection
     */
    public function publishers()
    {
        return $this->publishers;
    }

    /**
     * Removes a publisher from this game.
     * @param $publisher
     */
    public function removePublisher(Party $publisher)
    {
        $this->publishers->removeElement($publisher);
    }

    /**
     * Removes all publishers from this game.
     */
    public function clearPublishers()
    {
        foreach ($this->publishers as $publisher) {
            $this->removePublisher($publisher);
        }
    }

    /**
     * Returns all the tags of this game
     *
     * @return ArrayCollection
     */
    public function tags()
    {
        return $this->tags;
    }

    /**
     * Adds a tag to the game
     *
     * @param Tag $tag
     */
    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;
        $tag->addGame($this);
    }

    /**
     * Removes a tag from the game
     *
     * @param Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Clears all the tags from the game
     */
    public function clearTags()
    {
        foreach ($this->tags as $tag) {
            $this->removeTag($tag);
        }
    }

    /**
     * @param Organization organization
     * @return void
     */
    public function setOrganization(Organization $organization)
    {
        $this->organization = $organization;
    }

    /**
     * @return Organization
     */
    public function organization()
    {
        return $this->organization;
    }

    /**
     * @param  year_published
     * @return void
     */
    public function setYearPublished($year_published)
    {
        $this->year_published = $year_published;
    }

    /**
     * @return
     */
    public function year_published()
    {
        return $this->year_published;
    }

    /**
     * @param  website
     * @return void
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * @return
     */
    public function website()
    {
        return $this->website;
    }

    /**
     * @param  min_age
     * @return void
     */
    public function setMinAge($min_age)
    {
        $this->min_age = $min_age;
    }

    /**
     * @return
     */
    public function minAge()
    {
        return $this->min_age;
    }

    /**
     * @param  max_age
     * @return void
     */
    public function setMaxAge($max_age)
    {
        $this->max_age = $max_age;
    }

    /**
     * @return
     */
    public function maxAge()
    {
        return $this->max_age;
    }

    /**
     * @param  min_number_players
     * @return void
     */
    public function setMinNumberPlayers($min_number_players)
    {
        $this->min_number_players = $min_number_players;
    }

    /**
     * @return
     */
    public function minNumberPlayers()
    {
        return $this->min_number_players;
    }

    /**
     * @param  max_number_players
     * @return void
     */
    public function setMaxNumberPlayers($max_number_players)
    {
        $this->max_number_players = $max_number_players;
    }

    /**
     * @return
     */
    public function maxNumberPlayers()
    {
        return $this->max_number_players;
    }
}