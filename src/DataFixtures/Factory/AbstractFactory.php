<?php

namespace App\DataFixtures\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory as FakerFactory;
use Faker\Generator;

/**
 *
 */
abstract class AbstractFactory
{
    protected Generator $faker;
    public function __construct(
        public EntityManagerInterface $entityManager,
    ) {
        $this->faker = FakerFactory::create();
    }

    abstract public function __invoke(): object;
}
