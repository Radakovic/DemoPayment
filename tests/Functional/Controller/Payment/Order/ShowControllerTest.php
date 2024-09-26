<?php

namespace App\Tests\Functional\Controller\Payment\Order;

use App\Entity\MerchantOrder;
use App\Faker\Factory\MerchantOrderFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class ShowControllerTest extends WebTestCase
{
    private MerchantOrderFactory $orderFactory;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->orderFactory = self::getContainer()->get(MerchantOrderFactory::class);
        $this->entityManager = self::getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testShowForm(): void
    {
        $order = $this->createOrder();
        $url = sprintf('/orders/invoices/%s', $order->getId()->toString());
        $crawler = $this->client->request('GET', $url);
        $this->assertCorrectPage($order, $crawler);
    }

    public function testSubmitForm(): void
    {
        $order = $this->createOrder();
        $url = sprintf('/orders/invoices/%s', $order->getId()->toString());
        $crawler = $this->client->request('GET', $url);
        $this->assertCorrectPage($order, $crawler);

        $form = $crawler->selectButton('Submit')->form();

        $values = $form->getValues();

        $dsad= 'dasdsa';
    }

    private function assertCorrectPage(MerchantOrder $order, Crawler $crawler): void
    {
        self::assertResponseIsSuccessful();
        $h1 = $crawler->filter('h1')->text();
        $amount = $crawler->filter("input[name=merchant_order\[amount\]]")->attr('value');

        self::assertEquals($amount, ($order->getAmount() / 100));
        self::assertEquals('Create invoice', $h1);
    }

    private function createOrder(): MerchantOrder
    {
        $order = ($this->orderFactory)();
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }
}
