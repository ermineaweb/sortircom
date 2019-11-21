<?php

namespace App\Entity;


abstract class StatusEnum
{
	public const CREE = 0;
	public const OUVERTE = 1;
	public const CLOTURE = 2;
	public const EN_COURS = 3;
	public const PASSEE = 4;
	public const ANNULEE = 5;
	
	public function getStatus(int $nb) : string {
		$tabStatus = [
		CREE => "Créé",
		OUVERTE => "Ouverte",
		CLOTURE => "Clôturé",
		EN_COURS => "En cours",
		PASSEE => "Passée",
		ANNULEE => "Annulée",
		];
		
		return $tabStatus($nb);
	}
}
