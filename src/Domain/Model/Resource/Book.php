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

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="books", indexes={@ORM\Index(columns={"isbn"}), @ORM\Index(columns={"language"})})
 *
 */
class Book extends Resource
{

    /**
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $description;
    /**
     * @ORM\ManyToOne(targetEntity="Bakgat\Notos\Domain\Model\Resource\Image")
     */
    private $image;
    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $language;
    /**
     * @ORM\Column(type="string", length=20)
     */
    private $isbn;
    /**
     * @ORM\ManyToMany(targetEntity="Bakgat\Notos\Domain\Model\Identity\Party")
     * @ORM\JoinTable(name="book_authors",
     *      joinColumns={@ORM\JoinColumn(name="book_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="author_id", referencedColumnName="id")}
     * )
     *
     */
    private $authors;
    /**
     * @ORM\ManyToMany(targetEntity="Bakgat\Notos\Domain\Model\Identity\Organization")
     * @ORM\JoinTable(name="book_publishers",
     *      joinColumns={@ORM\JoinColumn(name="book_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="publisher_id", referencedColumnName="id")}
     * )
     *
     */
    private $publishers;
    /**
     * @ORM\ManyToMany(targetEntity="Bakgat\Notos\Domain\Model\Descriptive\Tag", inversedBy="books")
     * @ORM\JoinTable(name="book_tags",
     *      joinColumns={@ORM\JoinColumn(name="book_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      ))
     * @var ArrayCollection
     */
    private $tags;
    /**
     * @ORM\ManyToOne(targetEntity="Bakgat\Notos\Domain\Model\Identity\Organization")
     */
    private $organization;


    public function __construct(Name $name, Isbn $isbn)
    {
        parent::__construct($name);

        $this->setIsbn($isbn);

        $this->authors = new ArrayCollection;
        $this->publishers = new ArrayCollection;

    }

    public static function register(Name $name, Isbn $isbn)
    {
        return new Book($name, $isbn);
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
     * @param  language
     * @return void
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return
     */
    public function language()
    {
        return $this->language;
    }

    /**
     * @param Isbn isbn
     * @return void
     */
    public function setIsbn(Isbn $isbn)
    {
        $this->isbn = $isbn;
    }

    /**
     * @return Isbn
     */
    public function isbn()
    {
        return $this->isbn;
    }


    /**
     * Destroys a Book. (Soft delete)
     */
    public function destroy()
    {
        $this->setDeletedAt(new DateTime);
    }

    /**
     * Adds an author for this book.
     *
     * @param Party $author
     */
    public function addAuthor(Party $author)
    {
        $this->authors[] = $author;
    }

    /**
     * Returns the authors of this book.
     *
     * @return ArrayCollection
     */
    public function authors()
    {
        return $this->authors;
    }

    /**
     * Removes an author from this book.
     * @param $author
     */
    public function removeAuthor(Party $author)
    {
        $this->authors->removeElement($author);
    }

    /**
     * Removes all authors from this book.
     */
    public function clearAuthors()
    {
        $this->authors = new ArrayCollection;
    }

    /**
     * Adds a publisher for this book.
     *
     * @param Party $publisher
     */
    public function addPublisher(Party $publisher)
    {
        $this->publishers[] = $publisher;
    }

    /**
     * Returns the publishers of this book.
     *
     * @return ArrayCollection
     */
    public function publishers()
    {
        return $this->publishers;
    }

    /**
     * Removes a publisher from this book.
     * @param $publisher
     */
    public function removePublisher(Party $publisher)
    {
        $this->publishers->removeElement($publisher);
    }

    /**
     * Removes all publishers from this book.
     */
    public function clearPublishers()
    {
        $this->publishers = new ArrayCollection;
    }

    public function tags()
    {
        return $this->tags;
    }

    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;
        $tag->addBook($this);
    }

    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    public function clearTags()
    {
        $this->tags = new ArrayCollection;
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