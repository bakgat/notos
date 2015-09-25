<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 25/09/15
 * Time: 12:25
 */

namespace Bakgat\Notos\Domain\Services\Location;


use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use Bakgat\Notos\Domain\Model\Location\Blog;
use Bakgat\Notos\Domain\Model\Location\BlogRepository;
use Bakgat\Notos\Domain\Model\Location\URL;

class BlogService
{
    /** @var BlogRepository $blogRepo */
    private $blogRepo;
    /** @var OrganizationRepository $orgRepo */
    private $orgRepo;

    public function __construct(BlogRepository $blogRepository, OrganizationRepository $organizationRepository)
    {
        $this->blogRepo = $blogRepository;
        $this->orgRepo = $organizationRepository;
    }

    public function all($orgId)
    {
        /** @var Organization $organization */
        $organization = $this->orgRepo->organizationOfId($orgId);

        return $this->blogRepo->all($organization);
    }

    public function blogOfId($id)
    {
        return $this->blogRepo->blogOfId($id);
    }

    public function add($orgId, $data)
    {

        $organization = $this->orgRepo->organizationOfId($orgId);

        $name = new Name($data['name']);
        $url = new URL($data['url']);
        $description = isset($data['description']) ? $data['description'] : null;

        /** @var Blog $blog */
        $blog = Blog::register($name, $url, $organization);
        $blog->setDescription($description);

        $this->blogRepo->add($blog);
    }

    public function update($id, $data)
    {
        /** @var Blog $blog */
        $blog = $this->blogRepo->blogOfId($id);

        $name = new Name($data['name']);
        $url = new URL($data['url']);
        $description = isset($data['description']) ? $data['description'] : null;

        $blog->setName($name);
        $blog->setURL($url);
        $blog->setDescription($description);

        $this->blogRepo->update($blog);
    }

}