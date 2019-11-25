<?php

namespace App\Tests;

use App\Technical\Messages;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
	public function testLogin()
	{
		$client = self::createClient();
		$crawler = $client->request('GET', '/login');
		$this->assertResponseIsSuccessful();
		
		$form = $crawler->selectButton("Connexion")
			->form([
				'email' => 'mauvaislogin',
				'password' => 'mauvaspass',
			]);
		$client->submit($form);
		// le formulaire doit renvoyer une 302 vers la meme route
		$this->assertResponseRedirects("/login", 302);
		// mauvais identifiants : le message flash doit contenir le message d'erreur
//		$this->assertSelectorTextContains("html strong.flash-msg", Messages::LOGIN_ERROR);
	}
	
	public function testLogout()
	{
		$client = self::createClient();
		$crawler = $client->request('GET', '/logout');
		$this->assertResponseRedirects("", 302);
//		$this->assertSelectorTextContains("strong.flash-msg", Messages::LOGOUT_SUCCESS);
	}
}
