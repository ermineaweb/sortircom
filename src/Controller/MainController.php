<?php

namespace App\Controller;

use App\Technical\Alert;
use App\Technical\Messages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
	/**
	 * @Route("/", name="home")
	 */
	public function index()
	{
		return $this->redirectToRoute('event_index');
	}
	
	/**
	 * Lors du logout, il y a une redirection vers cette route,
	 * permettant d'ajouter un message Flash, puis on redirige vers la route que l'on souhaite
	 * Pour cette appli c'est la page de recherche de sorties
	 *
	 * @Route("/index/logout", name="index_logout")
	 */
	public function logout()
	{
		$this->addFlash(Alert::INFO, Messages::LOGOUT_SUCCESS);
		return $this->redirectToRoute('event_index');
	}
	
}
