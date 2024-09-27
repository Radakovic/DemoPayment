<?php

namespace App\Tests\Functional\Admin;

use App\Entity\Invoice;
use App\Enum\InvoiceStatusEnum;
use App\Faker\Factory\CallbackFactory;
use App\Faker\Factory\InvoiceFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InvoiceAdminTest extends WebTestCase
{
    private InvoiceFactory $invoiceFactory;
    private CallbackFactory $callbackFactory;
    private EntityManagerInterface $entityManager;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->invoiceFactory = self::getContainer()->get(InvoiceFactory::class);
        $this->callbackFactory = self::getContainer()->get(CallbackFactory::class);
        $this->entityManager = self::getContainer()
            ->get('doctrine')
            ->getManager();
    }
    public function testListInvoices(): void
    {
        $this->createInvoices();
        $crawler = $this->client->request('GET', '/admin/app/invoice/list');
        self::assertResponseIsSuccessful();
        $selector = 'body header ol li';
        $breadcrumb = $crawler->filter($selector)->last()->text();
        self::assertStringContainsString('Invoice List', $breadcrumb);

        $table = $crawler->filter('table');
        $rows = $crawler->filter('tbody>tr');
        self::assertCount(1, $table);
        self::assertCount(3, $rows);
    }

    public function testEmptyList(): void
    {
        $crawler = $this->client->request('GET', '/admin/app/invoice/list');
        self::assertResponseIsSuccessful();
        $selector = 'body header ol li';
        $breadcrumb = $crawler->filter($selector)->last()->text();
        self::assertStringContainsString('Invoice List', $breadcrumb);

        $table = $crawler->filter('table');
        self::assertCount(0, $table);
    }

    public function testShowInvoiceDetails(): void
    {
        $invoice = ($this->invoiceFactory)();
        $invoice->setStatus(InvoiceStatusEnum::SUCCESSFUL);
        $callback = ($this->callbackFactory)(invoice: $invoice);
        $invoice->addCallback($callback);
        $this->entityManager->persist($invoice);
        $this->entityManager->persist($callback);
        $this->entityManager->flush();
        $url = sprintf('/admin/app/invoice/%s/show', $invoice->getId()->toString());


        $crawler = $this->client->request('GET', $url);
        self::assertResponseIsSuccessful();
        $selector = 'body header ol li';
        $breadcrumb = $crawler->filter($selector)->last()->text();
        self::assertStringContainsString($invoice->__toString(), $breadcrumb);
        $boxHeader = $crawler->filter('div.box-header');
        self::assertStringContainsString('Invoice', $boxHeader->text());
        $tableRowDataSelector = 'tbody>tr>td';

        self::assertAnySelectorTextContains($tableRowDataSelector, $invoice->getId()->toString());
        self::assertAnySelectorTextContains($tableRowDataSelector, $invoice->getPaymentMethod());
        self::assertAnySelectorTextContains($tableRowDataSelector, $invoice->getStatus()->value);
        self::assertAnySelectorTextContains($tableRowDataSelector, $invoice->getExpirationDate()->format('F d, Y H:i'));
        self::assertAnySelectorTextContains($tableRowDataSelector, $invoice->getCreatedAt()->format('F d, Y H:i'));
        self::assertAnySelectorTextContains($tableRowDataSelector, $invoice->getDescription());
        self::assertAnySelectorTextContains($tableRowDataSelector, $invoice->getCallbacks()?->first()->getId()->toString());
    }

    private function createInvoices(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $invoice = ($this->invoiceFactory)();
            $this->entityManager->persist($invoice);
        }

        $this->entityManager->flush();
    }
}
