<?php

namespace App\Tests\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testSeeInputSelectAndButtonOnHomePage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSelectorExists('input[name="url"]');
        $this->assertSelectorExists('select[name="daysToLive"]');
        $this->assertSelectorExists('button[type="submit"]');
    }
}
