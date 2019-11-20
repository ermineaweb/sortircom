<?php

namespace App\Entity;


abstract class StatusEnum
{
	public const CREE = "Créée";
	public const OUVERTE = "Ouverte";
	public const CLOTURE = "Clôturée";
	public const EN_COURS = "En cours";
	public const PASSEE = "Passée";
	public const ANNULEE = "Annulée";
}
