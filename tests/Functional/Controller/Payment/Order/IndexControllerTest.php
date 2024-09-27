<?php

namespace App\Tests\Functional\Controller\Payment\Order;

use App\Faker\Factory\MerchantOrderFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    private MerchantOrderFactory $orderFactory;
    private EntityManagerInterface $entityManager;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->orderFactory = self::getContainer()->get(MerchantOrderFactory::class);
        $this->entityManager = self::getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testIndexPageWith3Orders(): void
    {
        $this->createOrdersForTests();
        $crawler = $this->client->request('GET', '/');
        self::assertResponseIsSuccessful();

        $h1 = $crawler->filter('h1')->text();
        $table = $crawler->filter('table');
        $rows = $crawler->filter('tbody>tr');

        self::assertStringContainsString('Merchant orders', $h1);
        self::assertCount(1, $table);
        self::assertCount(3, $rows);
    }

    public function testIndexPageWithoutOrders(): void
    {
        $crawler = $this->client->request('GET', '/');
        self::assertResponseIsSuccessful();

        $h1 = $crawler->filter('h1')->text();
        $table = $crawler->filter('table');

        self::assertStringContainsString('Merchant orders', $h1);
        self::assertCount(0, $table);
    }

    private function createOrdersForTests(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $order = ($this->orderFactory)();
            $this->entityManager->persist($order);
        }

        $this->entityManager->flush();
    }
}
