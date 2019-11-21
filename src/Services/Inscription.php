<?php

namespace App\Services;

use App\Entity\Event;
use App\Entity\StatusEnum;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Security;

/*
 * Inscription d'un utilisateur à une sortie
 * - l'utilisateur ne doit pas être déjà inscrit à la sortie
 * - l'évènement doit être ouvert
 * - la date d'inscription doit être inférieure à la date limite d'inscription
 * - il doit rester des places libres dans l'event
 */
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
	
	public function register()
	{
		if ($this->alreadyRegistered()) {
			$this->flashBag->add("danger", "Vous êtes déjà inscrit à cet évènement");
		} elseif (!$this->eventOpen()) {
			// si l'évènement n'est pas ouvert
			$this->flashBag->add("danger", "L'évènement n'est pas ouvert, votre inscription est refusée");
		} elseif ($this->limitDate()) {
			// si la date d'inscription est dépassée
			$this->flashBag->add("danger", "La date limite d'inscription est dépassée, votre inscription est refusée");
		} elseif ($this->isFull()) {
			// l'event est complet pour le moment
			$this->flashBag->add("danger", "Il n'y a plus de place pour le moment, un désistement peut avoir lieu, sait on jamais...");
		} else {
			// si tout est ok, on enregistre l'inscription
			$this->event->addUser($this->user);
			$this->userManager->persist($this->user);
			$this->userManager->flush();
		}
	}
	
	/*
	 * Vérifie si la date d'inscription n'est pas passée
	 */
	private function limitDate(): bool
	{
		return $this->event->getLimitdate()->diff(new \DateTime(), false)->format("%d") < 0;
	}
	
	/*
	 * Vérifie que l'event est bien en status ouvert
	 */
	private function eventOpen(): bool
	{
		return $this->event->getStatus() == StatusEnum::OUVERTE;
	}
	
	/*
	 * Vérifie que l'user n'est pas déjà inscrit
	 */
	private function alreadyRegistered(): bool
	{
		// on cherche si l'utilisateur est présent dans les inscriptions de la sortie
		return $this->event->getUsers()->contains($this->user);
	}
	
	private function isFull() : bool {
		return false;
	}
	
	
	public function getEvent(): ?Event
	{
		return $this->event;
	}
	
	public function setEvent($event): void
	{
		$this->event = $event;
	}
	
	public function getUser(): ?User
	{
		return $this->user;
	}
	
	public function setUser(User $user): void
	{
		$this->user = $user;
	}
	
}