<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\StatusEnum;
use App\Form\EventCancelType;
use App\Form\EventType;
use App\Repository\CityRepository;
use App\Repository\EventRepository;
use App\Repository\PlaceRepository;
use App\Repository\SchoolRepository;
use App\Services\EventCreation;
use App\Services\Inscription;
use App\Services\Withdraw;
use App\Technical\Alert;
use App\Technical\Messages;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route("/sortie", name="event_")
 */
class EventController extends AbstractController
{
	private $entityManager;
	private $eventRepository;
	
	public function __construct(EntityManagerInterface $entityManager, EventRepository $eventRepository)
	{
		$this->entityManager = $entityManager;
		$this->eventRepository = $eventRepository;
	}
	
	/**
	 * @Route("/{page}", name="index", methods={"GET","POST"}, requirements={"page"="\d+"})
	 */
	public function index(
		EventRepository $eventRepository,
		SchoolRepository $schoolRepository,
		Request $request,
		$page = 1): Response
	{
		$value = $request->request->get('search');
		$start = $request->request->get('start');
		$end = $request->request->get('end');
		$pastevents = $request->request->get('pastevents');
		$eventscreated = $request->request->get('eventscreated');
		dump($eventscreated);
		$registered = $request->request->get('registered');
		dump($registered);
		$notregistered = $request->request->get('notregistered');
		dump($notregistered);
		$user = $this->getUser();
		dump($user);
		$userId = $user->getId();
		dump($userId);
		// Sorties dont l'école du creator = l'école du user
		// TODO : Lorsqu'un user affiche la page la 1re fois, la school par défaut est la sienne
		if ($request->isMethod('get')) {
			$school = $user->getSchool();
			
		} else {
			$school = $request->request->get('school');
		}
		dump($school);
		
		// TODO : filtrer par school
		
		$paginator = $eventRepository->findByFilters($value, $start, $end, $school, $page, $pastevents, $eventscreated, $registered, $notregistered, $user, $userId);
		return $this->render('event/manage.html.twig', [
			'paginator' => $paginator,
			'schools' => $schoolRepository->findAll(),
			'page' => $page,
			'statusenum' => StatusEnum::getAllStatuses(),
			'statusstyles' => StatusEnum::getStatusStyles(),
		]);
	}

    /**
     * Cette route permet de créer une sortie :
     * - si la date de début de la sortie est supérieure à la date actuelle
     * - si le nombre maximum de participants est supérieur à 0
     * Alors :
     * - l'utilisateur en cours devient le créateur de l'annonce
     * - le statut de la sortie devient créee
     * @Route("/creer", name="new", methods={"GET","POST"})
     * @param Request $request
     * @param CityRepository $cityRepository
     * @param PlaceRepository $placeRepository
     * @param EventCreation $eventCreation
     * @return Response
     */
	public function new(Request $request, CityRepository $cityRepository, PlaceRepository $placeRepository, EventCreation $eventCreation): Response
	{
		$event = new Event();
		$form = $this->createForm(EventType::class, $event);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {

            $eventCreation->setEvent($event);
            $eventCreation->setUser($this->getUser());
            $eventCreation->creation();


				return $this->redirectToRoute('event_new');
		}
		
		return $this->render('event/new.html.twig', [
			'event' => $event,
			'form' => $form->createView(),
			'cities' => $cityRepository->findAll(),
			// A REFAIRE DANS UNE PROCHAINE VERSION
			// j'ai forcé les places à la première ville, il faudra le gérer en JAVASCRIPT dans le fichier places.js
			'places' => $placeRepository->findAll(),
		]);
	}
	
	/**
	 * Cette page permet d'affcher les détails d'une sortie en particulier :
	 * @Route("/show/{id}", name="show", methods={"GET"}, requirements={"id":"\d+"})
	 */
	public function show(Event $event): Response
	{
		return $this->render('event/show.html.twig', [
			'event' => $event,
		]);
	}
	
	/**
	 * Cette route permet de modifier une sortie :
	 * (Un message apparait pour confirmer la modification)
	 * - si l'utilisateur est bien le créateur
	 * - si la date de début de la sortie est supérieure à la date actuelle
	 * - si le statut de la sortie est : crée ou ouverte(publiée)
	 * @Route("/{id}/edit", name="edit", methods={"GET","POST"}, requirements={"id":"\d+"})
	 */
	public function edit(Request $request, Event $event, CityRepository $cityRepository): Response
	{
		$form = $this->createForm(EventType::class, $event);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()
			&& $this->getUser() == $event->getCreator()
			&& $event->getStart() > new \DateTime()
			&& ($event->getStatus() == StatusEnum::CREE || $event->getStatus() == StatusEnum::OUVERTE)) {
			
			$this->entityManager->flush();
			$this->addFlash(Alert::SUCCESS, Messages::EVENT_SUCCESS_EDIT);
			
			return $this->redirectToRoute('event_index');
		}
		
		return $this->render('event/edit.html.twig', [
			'event' => $event,
			'form' => $form->createView(),
			'cities' => $cityRepository->findAll(),
		]);
	}
	
	/**
	 * Cette route permet de supprimer une sortie :
	 * (Une sortie publiée ne peut plus être supprimée mais doit être anullée)
	 * - si l'utilisateur est bien le créateur
	 * - si la date de début de la sortie est supérieure à la date actuelle
	 * - si le statut de la sortie est : crée
	 * @Route("/{id}", name="delete", methods={"DELETE"}, requirements={"id":"\d+"})
	 */
	public function delete(Request $request, Event $event): Response
	{
		if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))
			&& $this->getUser() == $event->getCreator()
			&& $event->getStart() > new \DateTime()
			&& ($event->getStatus() == StatusEnum::CREE)) {
			
			$this->entityManager->remove($event);
			$this->entityManager->flush();
		}
		
		return $this->redirectToRoute('event_index');
	}
	
	/**
	 * Cette route permet d'annuler une sortie :
	 * (Une sortie non publiée ne peut pas être annulée mais doit être supprimée)
	 * - si l'utilisateur est bien le créateur
	 * - si la date de début de la sortie est supérieure à la date actuelle
	 * - si le champ "Motif de l'annulation :" du formulaire est renseigné
	 * - si le statut de la sortie est : ouverte
	 * @Route("/cancel/{id}", name="cancel",methods={"GET","POST"}, requirements={"id":"\d+"})
	 */
	public function cancel(Event $event, Request $request): Response
	{
		$form = $this->createForm(EventCancelType::class, $event);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()
			&& $this->getUser() == $event->getCreator()
			&& $event->getStart() > new \DateTime()
			&& $event->getStatus() == StatusEnum::OUVERTE) {
			
			if (empty($event->getCancel())) {
				$this->addFlash(Alert::WARNING, Messages::EVENT_ERROR_CANCEL);
				
			} else {
				$event->setStatus(StatusEnum::ANNULEE);
				$this->addFlash(Alert::SUCCESS, Messages::EVENT_SUCCESS_CANCEL);
				
				$this->entityManager->flush();
				return $this->redirectToRoute('event_index');
			}
		}
		
		return $this->render('event/cancel.html.twig', [
			'event' => $event,
			'form' => $form->createView(),
		]);
	}
	
	/**
	 * Inscription d'un utilisateur à une sortie
	 *
	 * @Route("/inscription/{id}", name="inscription", methods={"GET"}, requirements={"id":"\d+"})
	 */
	public function register(Event $event, Inscription $inscription): Response
	{
		$inscription->setEvent($event);
		$inscription->setUser($this->getUser());
		$inscription->register();
		
		return $this->render('event/show.html.twig', [
			'event' => $event,
		]);
	}
	
	/**
	 * Un utilisateur se désinscrit d'une sortie où il s'est inscrit
	 * @Route("/desinscrire/{id}", name="withdraw", methods={"GET"}, requirements={"id":"\d+"})
	 */
	public function withdraw(Event $event, Withdraw $withdraw): Response
	{
		$withdraw->setEvent($event);
		$withdraw->setUser($this->getUser());
		$withdraw->leave();
		
		return $this->render('event/show.html.twig', compact('event'));
	}
	
	/**
	 * Cette route permet de publier une annonce :
	 * (Une annonce est publiée après avoir été créée et non supprimée)
	 * - si l'utilisateur est bien le créateur
	 * - si la date de début de la sortie est supérieure à la date actuelle
	 * - si le statut de la sortie est : crée
	 * (Une annonce publiée ne peut plus être supprimée mais doit être annulée)
	 * @Route("/publish/{id}", name="publish", methods={"GET"}, requirements={"id":"\d+"})
	 */
	public function publish(Event $event): Response
	{
		if ($this->getUser() == $event->getCreator()
			&& $event->getStart() > new \DateTime()
			&& $event->getStatus() == StatusEnum::CREE) {
			
			$event->setStatus(StatusEnum::OUVERTE);
			$this->entityManager->flush();
			$this->addFlash(Alert::SUCCESS, Messages::EVENT_SUCCESS_PUBLISH);
		}
		
		return $this->render('event/show.html.twig', [
			'event' => $event,
		]);
	}
	
}
