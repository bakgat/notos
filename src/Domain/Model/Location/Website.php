<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/09/15
 * Time: 21:05
 */

namespace Bakgat\Notos\Domain\Model\Location;

use Bakgat\Notos\Domain\Model\Curricula\Objective;
use Bakgat\Notos\Domain\Model\Descriptive\Tag;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Resource\Image;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


use Illuminate\Support\Arr;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="websites", indexes={@ORM\Index(columns={"url"})})
 */
class Website extends Location
{
    /**
     * @ORM\Column(type="string")
     * @var URL $url
     * @JMS\Groups({"list", "detail","full"})
     */
    private $url;
    /**
     * @ORM\Column(type="text", length=65535, nullable=true)
     * @JMS\Groups({"detail","full"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Bakgat\Notos\Domain\Model\Resource\Image")
     * @JMS\Groups({"list", "detail","full"})
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="Bakgat\Notos\Domain\Model\Curricula\Objective", inversedBy="websites")
     * @ORM\JoinTable(name="website_objectives",
     *      joinColumns={@ORM\JoinColumn(name="website_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="objective_id", referencedColumnName="id")}
     *      )
     * @var ArrayCollection
     * @JMS\Groups({"detail","full"})
     **/
    private $objectives;

    /**
     * @ORM\ManyToMany(targetEntity="Bakgat\Notos\Domain\Model\Descriptive\Tag", inversedBy="websites")
     * @ORM\JoinTable(name="website_tags",
     *      joinColumns={@ORM\JoinColumn(name="website_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      ))
     * @JMS\Groups({"list", "detail","full"})
     * @var ArrayCollection
     */
    private $tags;

    public function __construct(Name $name, URL $url)
    {
        parent::__construct($name);
        $this->setUrl($url);

    }

    public static function register(Name $name, URL $url)
    {
        return new Website($name, $url);
    }

    /**
     * @param URL url
     * @return void
     */
    public function setUrl(URL $url)
    {
        $this->url = $url->toString();
    }

    /**
     * @return URL
     */
    public function url()
    {
        return URL::fromNative($this->url);
    }

    /**
     * @param description
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
     * @param Image image
     * @return void
     */
    public function setImage(Image $image)
    {
        $this->image = $image;
    }

    /**
     * @return Image
     */
    public function image()
    {
        return $this->image;
    }

    /**
     * Get all objectives that are associated with this website.
     * @return ArrayCollection
     */
    public function getObjectives()
    {
        return $this->objectives;
    }

    /**
     * Adds an objective to this website
     *
     * @param Objective $objective
     */
    public function addObjective(Objective $objective)
    {
        $this->objectives[] = $objective;
        $objective->addWebsite($this);
    }

    /**
     * Removes an objective for this website.
     *
     * @param Objective $objective
     */
    public function removeObjective(Objective $objective)
    {
        $this->objectives->removeElement($objective);
    }

    public function clearObjectives()
    {
        $this->objectives = new ArrayCollection;
    }

    /**
     * Get all tags that are associated with this website.
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Tag $tag
     */
    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;
        $tag->addWebsite($this);
    }

    /**
     * @param Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    public function clearTags() {
        $this->tags = new ArrayCollection;
    }


}