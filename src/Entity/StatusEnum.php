<?php

namespace App\Entity;


abstract class StatusEnum
{
	private const statuses = [
		CREE => "Créée",
		OUVERTE => "Ouverte",
		CLOTURE => "Clôturée",
		EN_COURS => "En cours",
		PASSEE => "Passée",
		ANNULEE => "Annulée",
	];
}
