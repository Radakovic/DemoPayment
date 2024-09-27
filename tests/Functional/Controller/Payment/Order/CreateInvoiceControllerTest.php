<?php

namespace App\Tests\Functional\Controller\Payment\Order;

use App\Entity\MerchantOrder;
use App\Faker\Factory\MerchantOrderFactory;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class CreateInvoiceControllerTest extends WebTestCase
{
    private MerchantOrderFactory $orderFactory;
    private EntityManagerInterface $entityManager;
    private KernelBrowser $client;
    private InvoiceRepository $invoiceRepository;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = self::createClient();
        $this->client->followRedirects();

        $this->orderFactory = self::getContainer()->get(MerchantOrderFactory::class);
        $this->invoiceRepository = self::getContainer()->get(InvoiceRepository::class);
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
        self::assertTrue(true);
    }

    public function testSubmitForm(): void
    {
        $order = $this->createOrder();
        $url = sprintf('/orders/invoices/%s', $order->getId()->toString());
        $crawler = $this->client->request('GET', $url);
        $this->assertCorrectPage($order, $crawler);

        $form = $crawler->selectButton('Submit')->form();
        $form->setValues([
            'merchant_order[id]' => $order->getId()->toString(),
            'merchant_order[country]' => $order->getCountry(),
            'merchant_order[currency]' => $order->getCurrency(),

            'merchant_order[invoice][paymentMethod]' => 'METHOD 2',
            'merchant_order[invoice][description]' => 'Some description',

            'merchant_order[payer][document]' => Uuid::uuid4()->toString(),
            'merchant_order[payer][firstName]' => 'Pera',
            'merchant_order[payer][lastName]' => 'Mitic',
            'merchant_order[payer][phone]' => 'no validation',
            'merchant_order[payer][email]' => 'test@example.com',
        ]);

        $crawler = $this->client->submit($form);

        $invoices = $this->invoiceRepository->findAll();
        self::assertCount(1, $invoices);
        $invoice = $invoices[0];
        self::assertSame($order->getId()->toString(), $invoice->getOrder()->getId()->toString());
        $uri = $crawler->getUri();
        $expectedUrl = sprintf('/invoices/%s', $invoice->getId()->toString());
        self::assertStringContainsString($expectedUrl, $uri);
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
