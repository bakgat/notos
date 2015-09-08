<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 26/06/15
 * Time: 11:12
 */

namespace Bakgat\Notos\Infrastructure\Repositories;


use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Kind;
use Bakgat\Notos\Domain\Model\KindRepository;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\Cache;

class KindCacheRepository implements KindRepository
{

    /** @var  EntityManagerInterface */
    private $em;
    /** @var  string */
    private $class;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->class = 'Bakgat\Notos\Domain\Model\Kind';
    }

    public function get($name)
    {
        $uppername = strtoupper($name);

        //create unique key for kind and uppercased name
        $key = md5('kind.' . $uppername);
        //cache has value return this one
        $cached = Cache::get($key);
        if ($cached) {
            return $cached;
        }

        //find or create in store

        $kind = $this->em->getRepository($this->class)
            ->findOneBy(['name' => $uppername]);
        if (!$kind) {
            $kind = new Kind(new Name($uppername));
            $this->em->persist($kind);
            $this->em->flush();
        }

        //cache it for next request
        Cache::forever($key, $kind);
        return Cache::get($key);
    }
}