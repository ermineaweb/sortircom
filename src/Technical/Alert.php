<?php

namespace App\Technical;
/**
 * Classe abstraite permettant de modifier facilement les
 * types d'alertes qui sont utilisées dans l'application.
 * Ces types d'alertes sont ensuites traduites en classe bootstrap
 */
class Alert
{
	public const SUCCESS = "success";
	public const DANGER = "danger";
	public const WARNING = "warning";
	public const PRIMARY = "primary";
	public const INFO = "info";
}