<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StatusControllerTest extends WebTestCase
{
    public function testSomething()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/status');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Status index');
    }
}
