<?php

namespace App\Services;

/*
* Création d'une sortie :
* - si la date de début de la sortie est supérieure à la date actuelle
* - si le nombre maximum de participants est supérieur à 0
* Alors :
* - l'utilisateur en cours devient le créateur de l'annonce
* - le statut de la sortie devient créee
*/

use App\Entity\Event;
use App\Entity\StatusEnum;
use App\Entity\User;
use App\Technical\Alert;
use App\Technical\Messages;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Security;

class EventCreation
{
    private $event;
    private $flashBag;
    private $eventManager;

    public function __construct(FlashBagInterface $flashBag, EntityManagerInterface $eventManager, Security $security)
    {
      $this->flashBag = $flashBag;
      $this->eventManager = $eventManager;
      $this->user = $security->getUser();
    }

    public function creation()
    {
        if ($this->isBeginningOk ()) {
            $this->flashBag->add(Alert::DANGER, Messages::EVENT_ERROR_NEW_DATE);
        } elseif ($this->isMaxsize()) {
            $this->flashBag->add(Alert::DANGER, Messages::EVENT_ERROR_MAXSIZE);
        } else {
            //Si tout est bon
            //L'utilisateur connecté qui crée l'event devient le creator
            $this->event->setCreator($this->getUser());
            // status par défaut au moment de la création
            $this->event->setStatus(StatusEnum::CREE);

            $this->eventManager->persist($this->event);
            $this->eventManager->flush();
            $this->flashBag->add(Alert::SUCCESS, Messages::EVENT_SUCCESS_NEW);
        }
    }

    /*
    * Vérifie que la date du début est supérieur à la date du jour
    */
   private function isBeginningOk(): bool
   {
       return $this->event->getStart() <= new \DateTime();
   }

    /*
     * Vérifie que le nombre de places de la sortie est supérieur à 0.
     */
   private function isMaxsize(): bool
   {
       return $this->event->getMaxsize() < 0;
   }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent($event): void
    {
        $this->event = $event;
    }

    public function getUser(): \Symfony\Component\Security\Core\User\UserInterface
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }


}





