<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 22/09/15
 * Time: 15:14
 */

namespace Bakgat\Notos\Domain\Services\Resource;


use Bakgat\Notos\Domain\Model\Descriptive\TagRepository;
use Bakgat\Notos\Domain\Model\Resource\Exceptions\BookNotFoundException;
use Bakgat\Notos\Domain\Model\Identity\Exceptions\OrganizationNotFoundException;
use Bakgat\Notos\Domain\Model\Identity\Isbn;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use Bakgat\Notos\Domain\Model\Identity\Party;
use Bakgat\Notos\Domain\Model\Identity\PartyRepository;
use Bakgat\Notos\Domain\Model\KindRepository;
use Bakgat\Notos\Domain\Model\Resource\AssetRepository;
use Bakgat\Notos\Domain\Model\Resource\Book;
use Bakgat\Notos\Domain\Model\Resource\BookRepository;
use Bakgat\Notos\Exceptions\NotFoundException;
use Bakgat\Notos\Exceptions\UnprocessableEntityException;
use Doctrine\Common\Collections\ArrayCollection;

class BookService
{
    /** @var BookRepository $bookRepo */
    private $bookRepo;
    /** @var OrganizationRepository $orgRepo */
    private $orgRepo;
    /** @var AssetRepository $assetRepo */
    private $assetsRepo;
    /** @var TagRepository $tagRepository */
    private $tagRepository;
    /** @var KindRepository $kindRepo */
    private $kindRepo;
    /** @var PartyRepository $partyRepo */
    private $partyRepo;


    public function __construct(BookRepository $bookRepository,
                                TagRepository $tagRepository,
                                OrganizationRepository $organizationRepository,
                                AssetRepository $assetRepository,
                                KindRepository $kindRepository,
                                PartyRepository $partyRepository)
    {
        $this->bookRepo = $bookRepository;
        $this->tagRepository = $tagRepository;
        $this->orgRepo = $organizationRepository;
        $this->assetsRepo = $assetRepository;
        $this->kindRepo = $kindRepository;
        $this->partyRepo = $partyRepository;
    }

    /**
     * @param $orgId
     * @return ArrayCollection
     * @throws OrganizationNotFoundException
     */
    public function all($orgId)
    {
        $organization = $this->orgRepo->organizationOfId($orgId);
        if (!$organization) {
            throw new OrganizationNotFoundException($orgId);
        }

        return $this->bookRepo->all($organization);
    }

    /**
     * Find a book by it's id
     *
     * @param $id
     * @return Book
     * @throws BookNotFoundException
     */
    public function bookOfId($id)
    {
        $book = $this->bookRepo->bookOfId($id);
        if (!$book) {
            throw new BookNotFoundException($id);
        }
        return $book;
    }

    /**
     * Adds a new book
     *
     * @param $orgId
     * @param $data
     * @return Book
     * @throws OrganizationNotFoundException
     * @throws UnprocessableEntityException
     */
    public function add($orgId, $data)
    {
        $organization = $this->orgRepo->organizationOfId($orgId);
        if (!$organization) {
            throw new OrganizationNotFoundException($orgId);
        }

        $name = $this->nameIsRequired($data);
        $isbn = $this->isbnIsRequired($data);

        $book = Book::register($name, $isbn);
        $book->setOrganization($organization);

        $this->setDescription($data, $book);
        $this->addImage($data, $book);
        $this->syncAuthors($data, $book);
        $this->syncPublishers($data, $book);
        $this->syncTags($data, $book);

        $this->bookRepo->add($book);

        return $book;
    }

    /**
     * Updates an existing book
     *
     * @param $id
     * @param $data
     * @return Book
     * @throws BookNotFoundException
     */
    public function update($id, $data)
    {
        $name = $this->nameIsRequired($data);
        $isbn = $this->isbnIsRequired($data);

        $book = $this->bookRepo->bookOfId($id);
        if (!$book) {
            throw new BookNotFoundException($id);
        }

        $book->setName($name);
        $book->setIsbn($isbn);
        $this->setDescription($data, $book);

        $this->addImage($data, $book);
        $this->syncAuthors($data, $book);
        $this->syncPublishers($data, $book);
        $this->syncTags($data, $book);

        $this->bookRepo->update($book);

        return $book;
    }


    /* ***************************************************
     * PRIVATE METHODS
     * **************************************************/
    /**
     * @param $data
     * @return mixed
     * @throws UnprocessableEntityException
     */
    private function nameIsRequired($data)
    {
        if (!isset($data['name'])) {
            throw new UnprocessableEntityException();
        }
        return new Name($data['name']);
    }

    /**
     * @param $data
     * @return mixed
     * @throws UnprocessableEntityException
     */
    private function isbnIsRequired($data)
    {
        if (!isset($data['isbn'])) {
            throw new UnprocessableEntityException();
        }
        return new Isbn($data['isbn']);
    }

    /**
     * @param $author
     * @return Party
     * @throws NotFoundException
     * @throws UnprocessableEntityException
     */
    private function findOrCreateAuthor($author)
    {
        if (isset($author['id'])) {
            $auth = $this->partyRepo->partyOfId($author['id']);
            if (!$auth) {
                throw new NotFoundException('author_not_found', $author['id']);
            }
        } else {
            $name = new Name($author['last_name']);

            $authKind = $this->kindRepo->get('author');

            $auth = new Party($name);
            $auth->setKind($authKind);
            $this->partyRepo->add($auth);
        }
        return $auth;
    }

    /**
     * @param $publisher
     * @return Party
     * @throws NotFoundException
     * @throws UnprocessableEntityException
     */
    private function findOrCreatePublisher($publisher)
    {
        if (isset($publisher['id'])) {
            $pub = $this->partyRepo->partyOfId($publisher['id']);
            if (!$pub) {
                throw new NotFoundException('publisher_not_found', $publisher['id']);
            }

        } else {
            $name = new Name($publisher['last_name']);
            $pubKind = $this->kindRepo->get('publisher');

            $pub = $this->partyRepo->partyOfNameAndKind($name, $pubKind);
            if (!$pub) {
                $pub = new Party($name);
                $pub->setKind($pubKind);
                $this->partyRepo->add($pub);
            }
        }
        return $pub;
    }

    /**
     * @param $data
     * @param $book
     * @return void
     */
    private function addImage($data, $book)
    {
        if (isset($data['image']) && isset($data['image']['guid'])) {
            $image = $this->assetsRepo->assetOfGuid($data['image']['guid']);
            $book->setImage($image);
        }
    }

    /**
     * @param $data
     * @param $book
     * @return void
     * @throws NotFoundException
     */
    private function syncAuthors($data, Book $book)
    {
        $this->bookRepo->clearAuthors($book);
        if (isset($data['authors'])) {
            foreach ($data['authors'] as $author) {
                $auth = $this->findOrCreateAuthor($author);
                $book->addAuthor($auth);
            }
        }
    }

    /**
     * @param $data
     * @param $book
     * @return voidf
     * @throws NotFoundException
     */
    private function syncPublishers($data, Book $book)
    {
        $this->bookRepo->clearPublishers($book);
        if (isset($data['publishers'])) {
            foreach ($data['publishers'] as $publisher) {
                $pub = $this->findOrCreatePublisher($publisher);
                $book->addPublisher($pub);
            }
        }
    }

    /**
     * @param $data
     * @param $book
     */
    private function syncTags($data, Book $book)
    {
        $this->bookRepo->clearTags($book);
        if (isset($data['tags'])) {
            foreach ($data['tags'] as $tag) {
                $t = $this->tagRepository->tagOfNameOrCreate(new Name($tag));
                $book->addTag($t);
            }
        }
    }

    /**
     * @param $data
     * @param $book
     * @return mixed
     */
    private function setDescription($data, Book $book)
    {
        if (isset($data['description']))
            $book->setDescription($data['description']);
        return $data;
    }
}