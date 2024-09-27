<?php

namespace App\Tests\Functional\Admin;

use App\Faker\Factory\InvoiceFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InvoiceAdminTest extends WebTestCase
{
    private InvoiceFactory $invoiceFactory;
    private EntityManagerInterface $entityManager;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->invoiceFactory = self::getContainer()->get(InvoiceFactory::class);
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

    private function createInvoices(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $invoice = ($this->invoiceFactory)();
            $this->entityManager->persist($invoice);
        }

        $this->entityManager->flush();
    }
}
