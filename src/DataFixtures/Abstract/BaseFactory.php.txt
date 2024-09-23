<?php

namespace App\DataFixtures\Abstract;

use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Faker\Provider\Base as BaseProvider;
use TechniekTeam\FakerBundle\Faker\Factory\AbstractFactory;
use TechniekTeam\FakerBundle\Faker\FakerGenerator;

abstract class BaseFactory
{
    /**
     * @var FakerGenerator
     */
    protected $faker;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var BaseProvider[]|iterable
     */
    private $providers;

    /**
     * @var AbstractFactory[]|iterable
     */
    private $factories;

    /**
     * @param EntityManagerInterface $entityManager
     * @param iterable|BaseProvider[] $providers
     * @param iterable|AbstractFactory[] $factories Contains all other factories from the container.
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        iterable $providers,
        iterable $factories
    ) {
        $this->faker = FakerFactory::create('nl_NL');

        // this load all provider in de App\Faker\Provider namespace
        foreach ($providers as $provider) {
            $this->faker->addProvider(new $provider($this->faker));
        }

        $this->entityManager = $entityManager;
        $this->providers = $providers;
        $this->factories = $factories;
    }

    /**
     * Returns the namespace of the entity the factory creates.
     *
     * @return string
     */
    abstract public function getEntity(): string;

    /**
     * Helper method which retrieves a factory based on the given namespace.
     *
     * @param string $entityNamespace
     *
     * @return AbstractFactory
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
     * Create an entity of the given namespace if the $entity parameter is null.
     *
     * @param string $entityNamespace
     * @param mixed|null $entity
     *
     * @return mixed $entity
     */
    protected function createEntityIfNotExists(string $entityNamespace, $entity = null)
    {
        if ($entity === null) {
            $entityFactory = $this->getOtherFactory($entityNamespace);
            $entity = $entityFactory();
            $this->entityManager->persist($entity);
        }

        return $entity;
    }

    /**
     * This method it executed to create a single entity matching the instance
     * given in AbstractFactory::getEntity()
     *
     * @return mixed
     */
    abstract public function __invoke();
}
