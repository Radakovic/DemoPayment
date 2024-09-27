<?php

namespace App\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

/**
 * The MigrationEventSubscriber fixes some annoyances when using `bin/console doctrine:migrations:diff` by cleaning
 * its output from consistent errors.
 */
#[AsDoctrineListener('postGenerateSchema')]
final class MigrationEventSubscriber
{

    /**
     * After Doctrine is done we want to fix the diff here. Otherwise it will
     * always create a down migration with 'CREATE SCHEMA public'.
     *
     * @throws SchemaException
     */
    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        $schema = $args->getSchema();
        $missingNamespaces = ['public'];

        // Create default namespace.
        foreach ($missingNamespaces as $namespace) {
            if (!$schema->hasNamespace($namespace)) {
                $schema->createNamespace($namespace);
            }
        }
    }
}
