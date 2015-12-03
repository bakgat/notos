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
     * @JMS\Exclude
     */
    private $websites;
    /**
     * @ORM\ManyToMany(targetEntity="Bakgat\Notos\Domain\Model\Resource\Book", mappedBy="tags")
     * @JMS\Exclude
     */
    private $books;


    public function __construct(TagName $name)
    {
        $this->setName($name);
    }

    public static function register(TagName $name)
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
     * @param TagName name
     * @return void
     */
    public function setName(TagName $name)
    {
        $this->name = strtolower($name->toString());
    }

    /**
     * @return TagName
     */
    public function name()
    {
        return TagName::fromNative($this->name);
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

    public function removeBook(Book $book) {

    }
}