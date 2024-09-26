<?php

namespace App\Tests\Functional\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Tests to validate the database schema and its mapping in PHP.
 */
final class SchemaDumpTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private EntityManager $entityManager;

    /**
     * @var SchemaTool
     */
    private SchemaTool $schemaTool;

    /**
     * @var ClassMetadata[]
     */
    private array $allMetadata;

    /**
     * Test whether we can create a DB schema from our entity mapping.
     */
    public function testSchemaDump(): void
    {
        self::assertNotCount(0, $this->schemaTool->getCreateSchemaSql($this->allMetadata));
    }

    /**
     * Test that we have an up to date database schema.
     */
    public function testSchemaUpToDate(): void
    {
        $updateSchemaSql = $this->schemaTool->getUpdateSchemaSql($this->allMetadata, true);
        self::assertCount(0, $updateSchemaSql);
    }

    /**
     * For each test we need to set up an {@see EntityManager} and the
     * Doctrine {@see SchemaTool}.
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->schemaTool = new SchemaTool($this->entityManager);
        $this->allMetadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
    }
}
