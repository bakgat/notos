<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 12/10/15
 * Time: 22:07
 */

namespace Bakgat\Notos\Infrastructure\Repositories\Resource;


use Bakgat\Notos\Domain\Model\Resource\Image;
use Bakgat\Notos\Domain\Model\Resource\ImageRepository;
use Doctrine\ORM\EntityManager;

class ImageDoctrineORMRepository implements ImageRepository
{
    /** @var EntityManager $em */
    private $em;
    /** @var string $class */
    private $class;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->class = 'Bakgat\Notos\Domain\Model\Resource\Image';
    }

    public function add(Image $image)
    {
        $this->em->persist($image);
        $this->em->flush();
    }

    public function update(Image $image)
    {
        $this->em->persist($image);
        $this->em->flush();
    }

    /**
     * Returns the metadata of the image with a certain name
     *
     * @param $name
     * @return mixed
     */
    public function imageOfName($name)
    {
        // TODO: Implement imageOfName() method.
    }
}