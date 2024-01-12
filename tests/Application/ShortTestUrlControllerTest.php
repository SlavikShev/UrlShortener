<?php

namespace App\Tests\Application;

use App\Repository\UrlRepository;

class ShortTestUrlControllerTest extends BaseWebTestClass
{
    public function testGotNotValidUrlAndReturnAnError(): void
    {
        $this->client->request('POST', '/short-url' , [], [], [], json_encode([
            'url' => 'not-valid-url',
            'daysToLive' => 1
        ]));

        $response = $this->client->getResponse();
        $arrayResponse = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('errors', $arrayResponse);
        $this->assertEquals("This value is not a valid URL.", $arrayResponse['errors'][0]);
        $this->assertResponseStatusCodeSame(400);
    }

    public function testGotNotValidDaysToLiveAndReturnAnError(): void
    {
        $this->client->request('POST', '/short-url' , [], [], [], json_encode([
            'url' => 'https://www.google.com',
            'daysToLive' => -1
        ]));

        $response = $this->client->getResponse();
        $arrayResponse = json_decode($response->getContent(), true);


        $this->assertArrayHasKey('errors', $arrayResponse);
        $this->assertEquals("This value should be between 1 and 30.", $arrayResponse['errors'][0]);
        $this->assertResponseStatusCodeSame(400);
    }

    public function testGotValidUrlAndSaveItToDatabase(): void
    {
        $url = $this->getContainer()->get(UrlRepository::class)->findOneBy(['value' => 'https://www.google.com']);
        $this->assertNull($url);

        $this->client->request('POST', '/short-url' , [], [], [], json_encode([
            'url' => 'https://www.google.com',
            'daysToLive' => 1
        ]));

        $response = $this->client->getResponse();
        $arrayResponse = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('shortUrl', $arrayResponse);
        $this->assertResponseStatusCodeSame(200);

        $url = $this->getContainer()->get(UrlRepository::class)->findOneBy(['value' => 'https://www.google.com']);
        $this->assertEquals('https://www.google.com', $url->getValue());
    }
}