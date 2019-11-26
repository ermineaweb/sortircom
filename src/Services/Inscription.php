<?php

namespace App\Services;

use App\Entity\Event;
use App\Entity\StatusEnum;
use App\Entity\User;
use App\Technical\Alert;
use App\Technical\Messages;
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
		if ($this->isAlreadyRegistered()) {
			$this->flashBag->add(Alert::DANGER, Messages::INSCRIPTION_ERROR_ALREADY_REGISTRED);
		} elseif (!$this->isEventOpen()) {
			// si l'évènement n'est pas ouvert
			$this->flashBag->add(Alert::DANGER, Messages::INSCRIPTION_ERROR_NO_OPEN);
		} elseif ($this->limitDate()) {
			// si la date d'inscription est dépassée
			$this->flashBag->add(Alert::DANGER, Messages::INSCRIPTION_ERROR_EVENT_LIMIT_DATE);
		} elseif ($this->isFull()) {
			// l'event est complet pour le moment
			$this->flashBag->add(Alert::DANGER, Messages::INSCRIPTION_ERROR_EVENT_IS_FULL);
		} elseif ($this->isCreator()) {
			// le créateur ne doit pas pouvoir s'inscrire
			$this->flashBag->add(Alert::DANGER, Messages::INSCRIPTION_ERROR_CREATOR);
		} else {
			// si tout est ok, on enregistre l'inscription
			$this->event->addUser($this->user);
			$this->userManager->persist($this->user);
			$this->userManager->flush();
			$this->flashBag->add(Alert::SUCCESS, Messages::INSCRIPTION_SUCCESS_SUBSCRIBE);
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
	private function isEventOpen(): bool
	{
		return $this->event->getStatus() == StatusEnum::OUVERTE;
	}
	
	/*
	 * Vérifie que l'user n'est pas déjà inscrit
	 */
	private function isAlreadyRegistered(): bool
	{
		// on cherche si l'utilisateur est présent dans les inscriptions de la sortie
		return $this->event->getUsers()->contains($this->user);
	}
	
	/*
	 * Vérifie qu'il y a des places dans l'évènement
	 */
	private function isFull(): bool
	{
		return false;
	}
	
	/*
	 * Vérifie que l'utilisateur qui s'inscrit n'est pas le créateur
	 */
	private function isCreator(): bool
	{
		return $this->user == $this->event->getCreator();
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