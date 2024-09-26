<?php

namespace App\DataFixtures\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use LogicException;

/**
 *
 */
abstract class AbstractFactory
{
    protected Generator $faker;

    /**
     * @var AbstractFactory[]
     */
    private iterable $factories;

    public function __construct(
        iterable $factories,
        public EntityManagerInterface $entityManager,
    ) {
        $this->faker = FakerFactory::create();
        $this->factories = $factories;
    }

    abstract public function getEntity(): string;
    abstract public function __invoke(): object;

    /**
     * Helper method which retrieves a factory based on the given namespace.
     */
    protected function getOtherFactory(string $entityNamespace): AbstractFactory
    {
        foreach ($this->factories as $factory) {
            if ($factory->getEntity() === $entityNamespace) {
                return $factory;
            }
        }

        throw new LogicException(
            "Couldn't find a factory which can generate a {$entityNamespace} entity."
        );
    }

    /**
     * Create an entity of the given namespace.
     */
    protected function createEntityIfNotExists(string $entityNamespace, $entity = null): mixed
    {
        if ($entity === null) {
            $entityFactory = $this->getOtherFactory($entityNamespace);
            $entity = $entityFactory();
            $this->entityManager->persist($entity);
        }

        return $entity;
    }
}
