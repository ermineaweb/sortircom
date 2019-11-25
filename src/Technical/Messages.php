<?php

namespace App\Technical;

/**
 * Class Messages
 * Cette classe permet de grouper les différents messages de l'application
 */
abstract class Messages
{
	/**
	 * EVENT MESSAGES
	 */
	public const NEW_EVENT_SUCCESS = "Votre sortie a bien été enregistrée, vous pouvez la modifier ou la publier";
	public const NEW_EVENT_ERROR = "Votre sortie n'a pas pu être ajoutée";
	public const PUBLISH_EVENT_SUCCESS = "Le statut de votre sortie est maintenant : ouverte";
	
	/**
	 * SECURITY MESSAGES
	 */
	public const LOGIN_SUCCESS = "Vous êtes à présent connecté, bienvenue";
	public const LOGIN_ERROR = "Identifiants incorrects";
	public const LOGOUT_SUCCESS = "Vous êtes à présent déconnecté";
	
}