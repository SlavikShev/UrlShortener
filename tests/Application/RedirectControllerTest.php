<?php

namespace App\Tests\Application;

use App\Repository\UrlRepository;
use App\Service\TransformUrlService;

class RedirectControllerTest extends BaseWebTestClass
{
    public function testRedirectToUrlAndIncreaseCounter(): void
    {
        $this->createUrl();
        $url = $this->getContainer()->get(UrlRepository::class)->findOneBy(['value' => 'https://www.google.com']);
        $transformUrlService = new TransformUrlService();
        $shortUrl = $transformUrlService->encodeNumberToString($url->getId());

        $this->client->request('GET', $shortUrl);

        $response = $this->client->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('https://www.google.com', $response->headers->get('location'));

        $url = $this->getContainer()->get(UrlRepository::class)->findOneBy(['value' => 'https://www.google.com']);
        $this->assertEquals(1, $url->getClicksCount());
    }

    public function testSeeNotFoundPageIfUrlDoesNotExist(): void
    {
        $this->client->request('GET', '/not-exist-url');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('.h1-title', 'This link not found');
    }
}