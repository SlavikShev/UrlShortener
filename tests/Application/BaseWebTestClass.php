<?php

namespace App\Tests\Application;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BaseWebTestClass extends WebTestCase
{
    protected EntityManager $entityManager;
    protected KernelBrowser $client;
    protected ContainerInterface $container;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->client->disableReboot();
        $this->container = $this->client->getContainer();

        $this->entityManager = $this->container->get('doctrine')->getManager();
        $this->entityManager->getConnection()->setNestTransactionsWithSavepoints(true);
        $this->entityManager->beginTransaction();
    }

    protected function tearDown(): void
    {
        $this->entityManager->rollback();

        parent::tearDown();
    }

    public function createUrl(): void
    {
        $this->client->request('POST', '/short-url' , [], [], [], json_encode([
            'url' => 'https://www.google.com',
            'daysToLive' => 1
        ]));
    }
}