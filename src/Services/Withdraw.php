<?php


namespace App\Services;

use App\Entity\StatusEnum;
use App\Technical\Alert;
use App\Technical\Messages;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Security;

/*
 * Se désinscrire
 * Contraintes :
 * - Vérifie que l'utilisateur connecté est inscrit à la sortie
 * - Vérifie que le statut de la sortie n'est pas "annulée", "passée" ou "en cours"
 * Libère une place
 */

class Withdraw
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
	
	public function leave()
	{
		if (!$this->isRegistered()) {
			$this->flashBag->add(Alert::DANGER, Messages::DESINSCRIPTION_ERROR_EVENT_NOT_REGISTERED);
		} elseif ($this->isEventCanceled()) {
			$this->flashBag->add(Alert::DANGER, Messages::DESINSCRIPTION_ERROR_EVENT_CANCELED);
		} elseif ($this->isEventFinished()) {
			$this->flashBag->add(Alert::DANGER, Messages::DESINSCRIPTION_ERROR_EVENT_FINISHED );
		} elseif ($this->isEventInProgress()) {
			$this->flashBag->add(Alert::DANGER, Messages::DESINSCRIPTION_ERROR_EVENT_IN_PROGRESS);
		} else {
			$this->event->removeUser($this->user);
			$this->userManager->persist($this->user);
			$this->userManager->flush();
			$this->flashBag->add(Alert::SUCCESS, Messages::DESINSCRIPTION_SUCCESS_UNSUBSCRIBE);
		}
	}
	
	/*
	 * Vérifie si l'utilisateur connecté est bien inscrit à la sortie consultée
	 */
	private function isRegistered(): bool
	{
		return $this->event->getUsers()->contains($this->user);
	}
	
	/*
	 * Vérifie si le statut de la sortie est "annulée"
	 */
	private function isEventCanceled(): bool
	{
		return $this->event->getStatus() == StatusEnum::ANNULEE;
	}
	
	/*
	 * Vérifie si le statut de la sortie est "passée"
	 */
	private function isEventFinished(): bool
	{
		return $this->event->getStatus() == StatusEnum::PASSEE;
	}
	
	/*
	 * Vérifie si le statut de la sortie est "en cours"
	 */
	private function isEventInProgress(): bool
	{
		return $this->event->getStatus() == StatusEnum::EN_COURS;
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
	
	/**
	 * @return FlashBagInterface
	 */
	public function getFlashBag(): FlashBagInterface
	{
		return $this->flashBag;
	}
	
	/**
	 * @param FlashBagInterface $flashBag
	 */
	public function setFlashBag(FlashBagInterface $flashBag): void
	{
		$this->flashBag = $flashBag;
	}
	
	/**
	 * @return EntityManagerInterface
	 */
	public function getUserManager(): EntityManagerInterface
	{
		return $this->userManager;
	}
	
	/**
	 * @param EntityManagerInterface $userManager
	 */
	public function setUserManager(EntityManagerInterface $userManager): void
	{
		$this->userManager = $userManager;
	}
	
}