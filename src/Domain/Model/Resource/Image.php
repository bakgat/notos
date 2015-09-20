<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 27/06/15
 * Time: 11:42
 */

namespace Bakgat\Notos\Domain\Model\Resource;

use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\RecordEvents;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="images", indexes={@ORM\Index(columns={"title", "directory", "filename"})})
 *
 */
class Image extends Resource
{


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $title;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;
    /**
     * @ORM\Column(type="string")
     */
    private $directory;
    /**
     * @ORM\Column(type="string", length=60)
     */
    private $filename;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $mime;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $time_shot;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $exif;

    public function __construct(Name $name, $filename)
    {
        parent::__construct($name);

        $this->setFilename($filename);
        $this->setDirectory($filename->directory());

    }

    public static function register(Name $name, $filename)
    {
        return new Image($name, $filename);
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
     * @param description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function description()
    {
        return $this->description;
    }

    /**
     * @param  directory
     * @return void
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    /**
     * @return
     */
    public function directory()
    {
        return $this->directory;
    }

    /**
     * @param  filename
     * @return void
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return
     */
    public function filename()
    {
        return $this->filename;
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
     * @param DateTime time_shot
     * @return void
     */
    public function setTimeShot(DateTime $time_shot)
    {
        $this->time_shot = $time_shot;
    }

    /**
     * @return DateTime
     */
    public function timeShot()
    {
        return $this->time_shot;
    }

    /**
     * @param  exif
     * @return void
     */
    public function setExif($exif)
    {
        $this->exif = $exif;
    }

    /**
     * @return
     */
    public function exif()
    {
        return $this->exif;
    }
}