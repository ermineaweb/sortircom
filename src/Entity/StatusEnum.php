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
	
	// retourne tous les status sous forme de tableaux, pour concertir un numéro de status en
	// chaine de caractère dans les twig
	public static function getAllStatuses() : array {
		return [
			0 => "Créé",
			1 => "Ouverte",
			2 => "Clôturé",
			3 => "En cours",
			4 => "Passée",
			5 => "Annulée",
		];
	}
	
	public static function getStatusStyles() : array {
		return [
			0 => "info",
			1 => "primary",
			2 => "danger",
			3 => "primary",
			4 => "danger",
			5 => "danger",
		];
	}
}
