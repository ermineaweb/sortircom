<?php

namespace App\Entity;


abstract class StatusEnum
{
	// permet d'avoir la complétion automatique et de ne pas manipuler de 0,1,3....
	public const CREE = 0;
	public const OUVERTE = 1;
	public const CLOTURE = 2;
	public const EN_COURS = 3;
	public const PASSEE = 4;
	public const ANNULEE = 5;
	
	// Renvoi une chaine de caractère en fonction du numéro de status passé en paramètre
	public static function getStatus(int $nb) : string {
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
