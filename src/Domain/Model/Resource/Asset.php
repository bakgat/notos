<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/10/15
 * Time: 08:35
 */

namespace Bakgat\Notos\Domain\Model\Resource;

use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as JMS;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="assets", indexes={@ORM\Index(columns={"title"})})
 *
 */
class Asset extends Resource
{
    /**
     * @ORM\Column(type="string", length=32, unique=true)
     */
    private $guid;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $title;
    /**
     * @ORM\Column(type="string")
     */
    private $mime;
    /**
     * @ORM\ManyToOne(targetEntity="Bakgat\Notos\Domain\Model\Identity\Organization")
     */
    private $organization;

    public function __construct($guid, Name $name, $mime, Organization $organization)
    {
        parent::__construct($name);

        $this->setGuid($guid);
        $this->setMime($mime);
        $this->setOrganization($organization);
    }

    public static function register(Name $name, $guid, $mime, Organization $organization)
    {
        return new Asset($guid, $name, $mime, $organization);
    }

    /**
     * @param  guid
     * @return void
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;
    }

    /**
     * @return
     */
    public function guid()
    {
        return $this->guid;
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
     * @param Organization organization
     * @return void
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
}