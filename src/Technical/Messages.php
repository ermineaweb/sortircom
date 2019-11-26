<?php

namespace App\Technical;

/**
 * Class Messages
 * Cette classe permet de grouper les différents messages de l'application
 */
abstract class Messages
{
	/*
	 * EVENT
	 */
	public const EVENT_SUCCESS_NEW = "Votre sortie a bien été enregistrée, vous pouvez la modifier ou la publier";
	public const EVENT_SUCCESS_EDIT = "Modification de la sortie a bien été effectuée ";
	public const EVENT_SUCCESS_PUBLISH = "Le statut de votre sortie est maintenant : ouverte";
	public const EVENT_SUCCESS_CANCEL = "L'annulation de la sortie est effectuée";
	public const EVENT_ERROR_CANCEL = "Vous devez indiquer un motif d'annulation de la sortie";
	public const EVENT_ERROR_NEW = "Votre sortie n'a pas pu être ajoutée";
	
	/*
	 * USER
	 */
	public const USER_SUCCESS_NEW = "L'utilisateur a bien été créé";
	public const USER_SUCCESS_EDIT = "Votre profil a bien été mis à jour";
	
	/*
	 * INSCRIPTION
	 */
	public const INSCRIPTION_ERROR_ALREADY_REGISTRED = "Vous êtes déjà inscrit à cet évènement";
	public const INSCRIPTION_ERROR_NO_OPEN = "L'évènement n'est pas ouvert, votre inscription est refusée";
	public const ERROR_EVENT_LIMIT_DATE = "La date limite d'inscription est dépassée, votre inscription est refusée";
	public const ERROR_EVENT_IS_FULL = "Il n'y a plus de place pour le moment, un désistement peut avoir lieu, sait on jamais...";
	
	/*
	 * DESINSCRIPTION
	 */
	public const ERROR_EVENT_NOT_REGISTERED = "Vous n'êtes pas inscrit à cet évènement";
	public const ERROR_EVENT_CANCELED = "Vous ne pouvez pas vous désinscrire d'un évènement annulé";
	public const ERROR_EVENT_FINISHED = "Vous ne pouvez pas vous désinscrire d'un évènement terminé";
	public const ERROR_EVENT_IN_PROGRESS = "Vous ne pouvez pas vous désinscrire d'un évènement en cours";
	public const SUCCESS_UNSUBSCRIBE = "Vous avez bien été désinscrit de la sortie ";

	/*
	 * SECURITY
	 */
	public const SECURITY = "Vous êtes à présent connecté, bienvenue";
	public const LOGIN_ERROR = "Identifiants incorrects";
	public const LOGOUT_SUCCESS = "Vous êtes à présent déconnecté";
	
}