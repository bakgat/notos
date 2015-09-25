<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 25/09/15
 * Time: 09:21
 */

namespace Bakgat\Notos\Domain\Model\Location;

use Bakgat\Notos\Domain\Model\Identity\Organization;
use Doctrine\ORM\Mapping as ORM;
use Bakgat\Notos\Domain\Model\Identity\Name;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="blogs", indexes={@ORM\Index(columns={"url"})})
 */
class Blog extends Location
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
     * @ORM\ManyToOne(targetEntity="Bakgat\Notos\Domain\Model\Identity\Organization")
     * @JMS\Exclude
     */
    private $organization;

    public function __construct(Name $name, URL $url, Organization $organization)
    {
        parent::__construct($name);
        $this->setURL($url);
        $this->setOrganization($organization);
    }

    public static function register(Name $name, URL $url, Organization $organization)
    {
        return new Blog($name, $url, $organization);
    }

    /**
     * @param URL url
     * @return void
     */
    public function setURL(URL $url)
    {
        $this->url = $url;
    }

    /**
     * @return URL
     */
    public function url()
    {
        return $this->url;
    }

    /**
     * @param  description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return
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
}