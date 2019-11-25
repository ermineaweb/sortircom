<?php

namespace App\Technical;

/**
 * Class Messages
 * Cette classe permet de grouper les différents messages
 * de l'application
 */
class Messages
{
	public const NEW_EVENT_SUCCESS = "Votre sortie a bien été enregistrée, vous pouvez la modifier ou la publier";
	public const NEW_EVENT_ERROR = "Votre sortie n'a pas pu être ajoutée";
	public const EDIT_EVENT_SUCCESS_1 = "Modification de la sortie ";
	public const EDIT_EVENT_SUCCESS_2 = " effectuée";
	public const PUBLISH_EVENT_SUCCESS = "Le statut de votre sortie est maintenant : ouverte";
    public const CANCEL_EVENT_SUCCESS = "L'annulation de la sortie est effectuée";
    public const CANCEL_EVENT_WARNING = "Vous devez indiquer un motif d\'annulation de la sortie";
	public const CREATE_USER_ADMIN = "L'utilisateur/trice a bien été crée/e";
	public const EDIT_USER_SUCCESS = "Votre profil a bien été mis à jour";

	
}