<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('submit')->form();
        $form['username'] = 'unmauvaisusername';
        $form['password'] = 'unmauvaispassword';
        $crawler = $client->submit($form);
        $this->assertResponseIsSuccessful();
        //$this->assertSelectorTextContains('.alert-danger', 'Username could not be found.');
    }
}
