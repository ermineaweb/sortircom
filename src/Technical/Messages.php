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
	public const EVENT_ERROR_LIMITDATE = "La date d'inscription doit être inférieure à la date de début";
	public const EVENT_ERROR_NEW_DATE = "La date de la sortie doit être supérieure à la date du jour";
	public const EVENT_ERROR_MAXSIZE = "Le nombre de participants doit être supérieur à 0";
	
	/*
	 * USER
	 */
	public const USER_SUCCESS_NEW = "L'utilisateur a bien été créé";
	public const USER_SUCCESS_EDIT = "Votre profil a bien été mis à jour";
	public const USER_SUCESS_DELETE= "L'utilisateur a bien été supprimé";
	
	/*
	 * INSCRIPTION
	 */
	public const INSCRIPTION_ERROR_ALREADY_REGISTRED = "Vous êtes déjà inscrit à cet évènement";
	public const INSCRIPTION_ERROR_NO_OPEN = "L'évènement n'est pas ouvert, votre inscription est refusée";
	public const INSCRIPTION_ERROR_EVENT_LIMIT_DATE = "La date limite d'inscription est dépassée, votre inscription est refusée";
	public const INSCRIPTION_ERROR_EVENT_IS_FULL = "Il n'y a plus de place pour le moment, un désistement peut avoir lieu, sait on jamais...";
	public const INSCRIPTION_ERROR_CREATOR = "Vous ne pouvez pas vous inscrire à votre propre évènement";
	public const INSCRIPTION_SUCCESS_SUBSCRIBE = "Vous êtes à présent inscrit à cette sortie";
	
	/*
	 * DESINSCRIPTION
	 */
	public const DESINSCRIPTION_ERROR_EVENT_NOT_REGISTERED = "Vous n'êtes pas inscrit à cet évènement";
	public const DESINSCRIPTION_ERROR_EVENT_CANCELED = "Vous ne pouvez pas vous désinscrire d'un évènement annulé";
	public const DESINSCRIPTION_ERROR_EVENT_FINISHED = "Vous ne pouvez pas vous désinscrire d'un évènement terminé";
	public const DESINSCRIPTION_ERROR_EVENT_IN_PROGRESS = "Vous ne pouvez pas vous désinscrire d'un évènement en cours";
	public const DESINSCRIPTION_SUCCESS_UNSUBSCRIBE = "Votre désinscription a bien été prise en comtpe";

	/*
	 * SECURITY
	 */
	public const SECURITY_SUCESS_LOGIN = "Vous êtes à présent connecté, bienvenue";
	public const SECURITY_ERROR_ERROR = "Identifiants incorrects";
	public const SECURITY_SUCCESS_LOGOUT = "Vous êtes à présent déconnecté";

	/*
	 * CITY
	 */
	public const CITY_SUCESS_NEW = "La ville a bien été ajoutée";
    public const CITY_SUCESS_EDIT = "La ville a bien été modifiée";
    public const CITY_SUCESS_DELETE = "La ville a bien été supprimée";

    /*
     * PLACE
     */
    public const PLACE_SUCESS_NEW = "Le lieu a bien été ajouté";
    public const PLACE_SUCESS_EDIT = "Le lieu a bien été modifié";
    public const PLACE_SUCESS_DELETE = "Le lieu a bien été supprimé";

    /*
     * ADMIN
     */
    public const ADMIN_WARNING = "Vous ne disposez pas des droits nécessaires";
}