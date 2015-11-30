<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/11/15
 * Time: 10:05
 */

namespace Bakgat\Notos\Seeds\Fixtures;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Exceptions\OrganizationNotFoundException;
use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use Bakgat\Notos\Domain\Services\Resource\AssetsManager;
use Bakgat\Notos\Infrastructure\Repositories\Identity\OrganizationDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Resource\AssetDoctrineORMRepository;
use Dflydev\ApacheMimeTypes\PhpRepository;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AssetsFixtures implements FixtureInterface
{
    /** @var OrganizationRepository $orgRepo */
    private $orgRepo;
    /** @var AssetsManager $assetsManager */
    private $assetsManager;

    private $file;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->orgRepo = new OrganizationDoctrineORMRepository($manager);
        $assetsRepo = new AssetDoctrineORMRepository($manager);
        $phpRepo = new PhpRepository();
        $this->assetsManager = new AssetsManager($assetsRepo, $phpRepo, $this->orgRepo);

        $this->createAssets();
    }

    public function createAssets()
    {
        $klimtoren = $this->getKlimtoren();
        for ($i = 0; $i < 10; $i++) {
            $image = $this->getImage($i, 'jpg');

            $asset = $this->assetsManager->upload($image, $klimtoren->id());
            unset($this->file);
        }
    }

    /* ***************************************************
     * PRIVATE METHODS
     * **************************************************/
    private function getKlimtoren()
    {
        $dn = new DomainName('klimtoren.be');
        $klimtoren = $this->orgRepo->organizationOfDomain($dn);
        if (!$klimtoren) {
            throw new OrganizationNotFoundException($dn->toString());
        }

        return $klimtoren;
    }

    private function getImage($i, $type = 'png')
    {
        $this->file = tempnam(sys_get_temp_dir(), 'upl');

        $this->createImage($i, $type);

        $name = 'image_test' . $i . '.' . $type;
        $mime = 'image/' . ($type === 'jpg' ? 'jpeg' : $type);
        $image = new UploadedFile(
            $this->file,
            $name,
            $mime
        );

        return $image;
    }

    private function createImage($i, $type)
    {
        $im = @imagecreate(110, 20);
        $background_color = imagecolorallocate($im, 0, 0, 0);
        $text_color = imagecolorallocate($im, 233, 14, 91);
        imagestring($im, 1, 5, 5, 'Test ' . $i, $text_color);
        if ($type === 'jpg') {
            imagejpeg($im, $this->file);
        } else if ($type === 'png') {
            imagepng($im, $this->file);
        } else if ($type === 'gif') {
            imagegif($im, $this->file);
        }
        imagepng($im, $this->file);
    }
}