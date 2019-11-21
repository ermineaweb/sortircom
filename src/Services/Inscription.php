<?php

namespace App\Services;

use App\Entity\Event;
use App\Entity\StatusEnum;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Security;

class Inscription
{
	private $event;
	private $user;
	private $flashBag;
	private $userManager;
	
	public function __construct(FlashBagInterface $flashBag, EntityManagerInterface $userManager, Security $security)
	{
		$this->flashBag = $flashBag;
		$this->userManager = $userManager;
		$this->user = $security->getUser();
	}
	
	public function inscrire()
	{
		
		if (!$this->eventOpen()) {
			// si l'évènement n'est pas ouvert
			$this->flashBag->addFlash("danger", "L'évènement n'est pas ouvert, votre inscription est refusée");
		} elseif ($this->limitDate()) {
			// si la date d'inscription est dépassée
			$this->flashBag->addFlash("danger", "La date limite d'inscription est dépassée, votre inscription est refusée");
		} else {
			// si tout est ok, on enregistre l'inscription
			$this->event->addUser($this->user);
			$this->userManager->persist($this->user);
			$this->userManager->flush();
		}
	}
	
	private function limitDate(): bool
	{
		return $this->event->getLimitdate()->diff(new \DateTime(), false)->format("%d") > 0;
	}
	
	private function eventOpen(): bool
	{
		return $this->event->getStatus() == StatusEnum::OUVERTE;
	}
	
	/**
	 * @return mixed
	 */
	public function getEvent()
	{
		return $this->event;
	}
	
	/**
	 * @param mixed $event
	 */
	public function setEvent($event): void
	{
		$this->event = $event;
	}
	
	/**
	 * @return \Symfony\Component\Security\Core\User\UserInterface|null
	 */
	public function getUser(): ?\Symfony\Component\Security\Core\User\UserInterface
	{
		return $this->user;
	}
	
	/**
	 * @param \Symfony\Component\Security\Core\User\UserInterface|null $user
	 */
	public function setUser(?\Symfony\Component\Security\Core\User\UserInterface $user): void
	{
		$this->user = $user;
	}
	
}