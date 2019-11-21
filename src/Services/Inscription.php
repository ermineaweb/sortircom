<?php

namespace App\Services;

use App\Entity\Event;
use App\Entity\StatusEnum;
use App\Entity\User;

class Inscription
{
	
	public function limitDate($event): bool
	{
		return $event->getLimitdate()->diff(new \DateTime()) > 0;
	}
	
	public function eventOpen($event): bool
	{
		return $event->getStatus() == StatusEnum::OUVERTE;
	}
	
}