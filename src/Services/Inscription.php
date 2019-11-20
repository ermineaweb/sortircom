<?php

namespace App\Services;

use App\Entity\Event;
use App\Entity\StatusEnum;
use App\Entity\User;

class Inscription
{
	private $user;
	private $event;
	
	public function __construct(User $user, Event $event)
	{
		$this->user = $user;
		$this->event = $event;
	}
	
	public function limitDate(): bool
	{
		return $this->event->getLimitdate()->diff(new \DateTime()) > 0;
	}
	
	public function eventOpen(): bool
	{
		return $this->event->getStatus() == StatusEnum::OUVERTE;
	}
	
	/**
	 * @return User
	 */
	public function getUser(): User
	{
		return $this->user;
	}
	
	/**
	 * @param User $user
	 */
	public function setUser(User $user): void
	{
		$this->user = $user;
	}
	
	/**
	 * @return Event
	 */
	public function getEvent(): Event
	{
		return $this->event;
	}
	
	/**
	 * @param Event $event
	 */
	public function setEvent(Event $event): void
	{
		$this->event = $event;
	}
	
	
}