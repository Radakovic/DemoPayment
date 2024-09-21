<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        // Request a specific page
        $crawler = $client->request('GET', '/');

        $response = $client->getResponse()->getContent();
        self::assertResponseIsSuccessful();
        self::assertJsonStringEqualsJsonString('{"data":"Djes Radujisa!!!"}', $response);
    }
}
