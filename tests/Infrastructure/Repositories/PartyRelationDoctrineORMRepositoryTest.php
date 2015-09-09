<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 25/06/15
 * Time: 11:12
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Email;
use Bakgat\Notos\Domain\Model\Identity\Gender;
use Bakgat\Notos\Domain\Model\Identity\HashedPassword;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\User;
use Bakgat\Notos\Domain\Model\Identity\Username;
use Bakgat\Notos\Domain\Model\Relations\PartyRelation;
use Bakgat\Notos\Domain\Model\Relations\Relation;
use Bakgat\Notos\Infrastructure\Repositories\KindCacheRepository;
use Bakgat\Notos\Infrastructure\Repositories\OrganizationDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\PartyRelationDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\UserDoctrineORMRepository;
use Bakgat\Notos\Test\EmTestCase;
use Bakgat\Notos\Tests\Infrastructure\Repositories\Fixtures\KindFixtures;
use Bakgat\Notos\Tests\Infrastructure\Repositories\Fixtures\OrganizationFixtures;
use Bakgat\Notos\Tests\Infrastructure\Repositories\Fixtures\PartyRelationFixtures;
use Bakgat\Notos\Tests\Infrastructure\Repositories\Fixtures\UserFixtures;
use DateTime;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class PartyRelationDoctrineORMRepositoryTest extends EmTestCase
{
    /** @var  PartyRelationDoctrineORMRepository */
    private $repository;
    /** @var  UserDoctrineORMRepository */
    private $userRepo;
    /** @var  OrganizationDoctrineORMRepository */
    private $orgRepo;
    /** @var  KindCacheRepository */
    private $kindRepo;


    public function setUp()
    {
        parent::setUp();

        $this->kindRepo = new KindCacheRepository($this->em);
        $this->repository = new PartyRelationDoctrineORMRepository($this->em);
        $this->userRepo = new UserDoctrineORMRepository($this->em);
        $this->orgRepo = new OrganizationDoctrineORMRepository($this->em);

        //$this->loader->addFixture(new KindFixtures);
        $this->loader->addFixture(new UserFixtures);
        $this->loader->addFixture(new OrganizationFixtures);
        $this->loader->addFixture(new PartyRelationFixtures);
    }

    private function getContext()
    {
        return $this->userRepo->userOfUsername(new Username('ulrike.drieskens@gmail.com'));
    }

    private function getReference()
    {
        return $this->orgRepo->organizationOfDomain(new DomainName('klimtoren.bez'));
    }

    /**
     * @test
     * @group partyRelation
     */
    public function should_add_relation()
    {
        $this->executor->execute($this->loader->getFixtures());

        $rebekka_fn = new Name('Rebekka');
        $rebekka_ln = new Name('Buyse');
        $rebekka_username = new Username('rebekka.buyse@klimtoren.bez');
        $rebekka_pwd = new HashedPassword(md5('password'));
        $rebekka_email = new Email('rebekka.buyse@klimtoren.bez');
        $rebekka_gender = new Gender('F');
        $rebekka = User::register($rebekka_fn, $rebekka_ln, $rebekka_username, $rebekka_pwd, $rebekka_email, $rebekka_gender);
        $this->userRepo->add($rebekka);

        $employee = $this->kindRepo->get('employee');

        $rel = PartyRelation::register($rebekka, $this->getReference(), $employee);
        $this->repository->add($rel);

        $this->em->clear();

        $relations = $this->repository->referencesOfContext($this->userRepo->userOfUsername($rebekka_username));
        $this->assertCount(1, $relations);
        $this->assertEquals($relations[0]->context()->username(), $rebekka_username->toString());

        $relations = $this->repository->contextOfReference($this->getReference());
        $this->assertCount(5, $relations);
    }

    /**
     * @test
     * @group partyRelation
     */
    public function should_get_relations_by_context()
    {
        $this->executor->execute($this->loader->getFixtures());
        $relations = $this->repository->referencesOfContext($this->getContext());

        $this->assertCount(2, $relations);
        $this->assertEquals($relations[0]->context()->username(), new Username('ulrike.drieskens@gmail.com'));
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\Organization', $relations[0]->reference());
    }

    /**
     * @test
     * @group partyRelation
     */
    public function should_get_relations_by_context_and_kind()
    {
        $this->executor->execute($this->loader->getFixtures());
        $relations = $this->repository->referencesOfContextByKind($this->getContext(), $this->kindRepo->get('employee'));

        $this->assertCount(1, $relations);
        $this->assertEquals($relations[0]->context()->username(), new Username('ulrike.drieskens@gmail.com'));
    }

    /**
     * @test
     * @group partyRelation
     */
    public function should_get_relations_by_reference()
    {
        $this->executor->execute($this->loader->getFixtures());
        $relations = $this->repository->contextOfReference($this->getReference());

        $this->assertCount(4, $relations);
        $this->assertEquals($relations[0]->reference()->domainName(), new DomainName('klimtoren.bez'));
    }

    /**
     * @test
     * @group partyRelation
     */
    public function should_get_relations_by_reference_and_kind()
    {
        $this->executor->execute($this->loader->getFixtures());
        $relations = $this->repository->contextOfReferenceByKind($this->getReference(), $this->kindRepo->get('employee'));

        $this->assertCount(2, $relations);
        $this->assertEquals($relations[0]->reference()->domainName(), new DomainName('klimtoren.bez'));
    }

    /**
     * @test
     * @group partyRelation
     */
    public function should_destroy_relation()
    {
        $this->executor->execute($this->loader->getFixtures());
        $relations = $this->repository->referencesOfContext($this->getContext());
        $this->assertCount(2, $relations);

        $this->em->clear();

        $this->repository->destroy($this->getContext(), $this->getReference(), $this->kindRepo->get('employee'));

        $this->em->clear();

        $relations = $this->repository->referencesOfContext($this->getContext());
        $this->assertCount(1, $relations);
    }


    /**
     * @test
     * @group partyRelation
     */
    public function should_destroy_relation_before_date()
    {
        $this->executor->execute($this->loader->getFixtures());
        $relations = $this->repository->referencesOfContext($this->getContext());
        $this->assertCount(2, $relations);


        sleep(1);

        $this->repository->destroyBefore($this->getContext(), $this->getReference(), new DateTime, $this->kindRepo->get('employee'));
        $this->em->clear();

        $relations = $this->repository->referencesOfContext($this->getContext());
        $this->assertCount(1, $relations);
    }

    /**
     * @test
     * @group partyRelation
     */
    public function should_not_destroy_relation_after_date()
    {
        $this->executor->execute($this->loader->getFixtures());
        $relations = $this->repository->referencesOfContext($this->getContext());
        $this->assertCount(2, $relations);


        sleep(1);

        $this->repository->destroyBefore($this->getContext(), $this->getReference(), new DateTime('2014/01/01'), $this->kindRepo->get('employee'));
        $this->em->clear();

        $relations = $this->repository->referencesOfContext($this->getContext());
        $this->assertCount(2, $relations);
    }

}
