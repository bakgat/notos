<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/10/15
 * Time: 08:35
 */

namespace Bakgat\Notos\Domain\Model\Resource;

use Bakgat\Notos\Domain\Model\Identity\Guid;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as JMS;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="assets", indexes={@ORM\Index(columns={"title"})})
 * @JMS\ExclusionPolicy("none")
 */
class Asset extends Resource
{
    /**
     * @ORM\Column(type="string", length=32, unique=true)
     * @JMS\Groups({"list","detail"})
     */
    private $guid;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @JMS\Groups({"list","detail"})
     */
    private $title;
    /**
     * @ORM\Column(type="string")
     * @JMS\Groups({"list","detail"})
     */
    private $mime;
    /**
     * @ORM\ManyToOne(targetEntity="Bakgat\Notos\Domain\Model\Identity\Organization", inversedBy="assets")
     * @JMS\Exclude
     */
    private $organization;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string $path
     * @JMS\Groups({"list","detail"})
     */
    private $path;

    public function __construct(Guid $guid, Name $name, $mime, Organization $organization)
    {
        parent::__construct($name);

        $this->setGuid($guid);
        $this->setPath($guid->toPath());
        $this->setMime($mime);
        $this->setOrganization($organization);
    }

    public static function register(Name $name, Guid $guid, $mime, Organization $organization)
    {
        return new Asset($guid, $name, $mime, $organization);
    }

    /**
     * @param Guid $guid
     */
    public function setGuid(Guid $guid)
    {
        $this->guid = $guid->toString();
    }

    /**
     * @return Guid
     */
    public function guid()
    {
        return Guid::fromNative($this->guid);
    }

    /**
     * @param  title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @param  mime
     * @return void
     */
    public function setMime($mime)
    {
        $this->mime = $mime;
    }

    /**
     * @return
     */
    public function mime()
    {
        return $this->mime;
    }

    /**
     * @param Organization $organization
     */
    public function setOrganization(Organization $organization)
    {
        $this->organization = $organization;
        $organization->addAsset($this);
    }

    /**
     * @return Organization
     */
    public function organization()
    {
        return $this->organization;
    }

    /**
     * @param path
     * @return void
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function path()
    {
        return $this->path;
    }
}