<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
	public function testLogin()
	{
		$client = static::createClient();
		// on vient sur la route /login
		$crawler = $client->request('GET', '/login');
		// on vérifie que la réponse est positive
		$this->assertResponseIsSuccessful();
		
		/*
		 * MAUVAIS IDENTIFIANTS
		 */
		// on prépare le formulaire de login
		$form = $crawler->selectButton("Connexion")
			->form([
			'email' => 'mauvaislogin',
			'password' => 'mauvaspass',
		]);
		// on soumet le formulaire
		$client->submit($form);
		// le formulaire doit renvoyer une 302 vers la meme route
		$this->assertResponseRedirects("/login", 302);
		//TODO : on ne doit pas etre connecté (mauvais identifiants)
		
		/*
		 * TODO identifiants valides
		 * IDENTIFIANTS VALIDES
		 */
		$form = $crawler->selectButton("Connexion")
			->form([
				'email' => 'mauvaislogin',
				'password' => 'mauvaspass',
			]);
		// on soumet le formulaire
		$client->submit($form);
		// le formulaire doit renvoyer une 302 vers la meme route
		$this->assertResponseRedirects("/login", 302);
	}
}
