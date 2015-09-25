<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/09/15
 * Time: 21:37
 */

namespace Bakgat\Notos\Domain\Model\Descriptive;

use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Location\Website;
use Bakgat\Notos\Domain\Model\Resource\Book;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="tags")
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @JMS\Groups({"list", "detail"})
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     * @JMS\Groups({"list", "detail"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Bakgat\Notos\Domain\Model\Location\Website", mappedBy="tags")
     */
    private $websites;
    /**
     * @ORM\ManyToMany(targetEntity="Bakgat\Notos\Domain\Model\Resource\Book", mappedBy="tags")
     */
    private $books;


    public function __construct(Name $name)
    {
        $this->setName($name);
    }

    public static function register(Name $name)
    {
        return new Tag($name);
    }

    /**
     * @return mixed
     */
    public function id() {
        return $this->id;
    }

    /**
     * @param Name name
     * @return void
     */
    public function setName(Name $name)
    {
        $this->name = strtolower($name->toString());
    }

    /**
     * @return Name
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function websites() {
        return $this->websites;
    }

    /**
     * @param Website $website
     */
    public function addWebsite(Website $website)
    {
        $this->websites[] = $website;
    }

    /**
     * @return mixed
     */
    public function books() {
        return $this->books;
    }

    /**
     * @param Book $book
     */
    public function addBook(Book $book) {
        $this->books[] = $book;
    }
}