<?php

namespace App\Tests\Application;

use App\Repository\UrlRepository;
use App\Service\TransformUrlService;

class StatsControllerTest extends BaseWebTestClass
{
    public function testSeeStatsForUrl(): void
    {
        $this->createUrl();
        $url = $this->getContainer()->get(UrlRepository::class)->findOneBy(['value' => 'https://www.google.com']);
        $transformUrlService = new TransformUrlService();
        $shortUrl = $transformUrlService->encodeNumberToString($url->getId());

        $this->client->request('GET', $shortUrl . '/stats');

        $this->assertSelectorTextContains('.full-link', 'Full link: ' . $url->getValue());
        $this->assertSelectorTextContains('.short-link', $shortUrl);
        $this->assertSelectorTextContains('.clicks', 'Clicks: ' . $url->getClicksCount());
    }

    public function testSeeNotFoundPageIfUrlDoesNotExist(): void
    {
        $this->client->request('GET', '/not-exist-url/stats');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('.h1-title', 'This link not found');
    }
}
