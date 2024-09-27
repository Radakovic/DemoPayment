<?php

namespace App\Tests\Functional\Admin;

use App\Enum\InvoiceStatusEnum;
use App\Faker\Factory\CallbackFactory;
use App\Faker\Factory\InvoiceFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CallbackAdminTest extends WebTestCase
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

    public function testListCallbacks(): void
    {
        $this->createCallbacks();
        $crawler = $this->client->request('GET', '/admin/app/callback/list');
        self::assertResponseIsSuccessful();
        $selector = 'body header ol li';
        $breadcrumb = $crawler->filter($selector)->last()->text();
        self::assertStringContainsString('Callback List', $breadcrumb);

        $table = $crawler->filter('table');
        $rows = $crawler->filter('tbody>tr');
        self::assertCount(1, $table);
        self::assertCount(3, $rows);
    }

    public function testEmptyCallbackList(): void
    {
        $crawler = $this->client->request('GET', '/admin/app/callback/list');
        self::assertResponseIsSuccessful();
        $selector = 'body header ol li';
        $breadcrumb = $crawler->filter($selector)->last()->text();
        self::assertStringContainsString('Callback List', $breadcrumb);

        $table = $crawler->filter('table');
        self::assertCount(0, $table);
    }

    public function testShowCallbackDetails(): void
    {
//        $invoice = ($this->invoiceFactory)();
//        $invoice->setStatus(InvoiceStatusEnum::SUCCESSFUL);
        $callback = ($this->callbackFactory)();
        $invoice = $callback->getInvoice();
        $invoice->addCallback($callback);
        $this->entityManager->persist($invoice);
        $this->entityManager->persist($callback);
        $this->entityManager->flush();
        $url = sprintf('/admin/app/callback/%s/show', $callback->getId()->toString());


        $crawler = $this->client->request('GET', $url);
        self::assertResponseIsSuccessful();
        $selector = 'body header ol li';
        $breadcrumb = $crawler->filter($selector)->last()->text();
        self::assertStringContainsString($callback->__toString(), $breadcrumb);
        $boxHeader = $crawler->filter('div.box-header');
        self::assertStringContainsString('Callback', $boxHeader->text());
        $tableRowDataSelector = 'tbody>tr>td';

        self::assertAnySelectorTextContains($tableRowDataSelector, $callback->getId()->toString());
        self::assertAnySelectorTextContains($tableRowDataSelector, $callback->getInvoice()->getId()->toString());
        self::assertAnySelectorTextContains($tableRowDataSelector, $callback->getCreatedAt()->format('F d, Y H:i'));
    }

    private function createCallbacks(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $callback = ($this->callbackFactory)();
            $this->entityManager->persist($callback);
        }

        $this->entityManager->flush();
    }
}
