<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\StatusEnum;
use App\Form\EventType;
use App\Repository\CityRepository;
use App\Repository\EventRepository;
use App\Repository\SchoolRepository;
use App\Services\Inscription;
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
	 * @Route("/", name="index", methods={"GET","POST"})
	 */
	public function index(EventRepository $eventRepository, SchoolRepository $schoolRepository, Request $request): Response
	{
		$value = $request->request->get('search');
		$start = $request->request->get('start');
		$end = $request->request->get('end');
		$school = $request->request->get('school');
		return $this->render('event/manager.html.twig', [
			'events' => $eventRepository->findByFilters($value, $start, $end, $school),
			'schools' => $schoolRepository->findAll()
		
		]);
	}
	
	/**
	 * @Route("/creer", name="new", methods={"GET","POST"})
	 */
	public function new(Request $request, CityRepository $cityRepository): Response
	{
		$event = new Event();
		$form = $this->createForm(EventType::class, $event);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
		    //Attribution statut par défaut
		    $event->setStatus(StatusEnum::CREE);
		    // On hydrate l'organisateur de l'event
            $event->setCreator($this->getUser());

			$this->entityManager->persist($event);
			$this->entityManager->flush();
			
			return $this->redirectToRoute('event_index');
		}
		
		return $this->render('event/new.html.twig', [
			'event' => $event,
			'form' => $form->createView(),
			'cities' => $cityRepository->findAll(),
		]);
	}
	
	/**
	 * @Route("/{id}", name="show", methods={"GET"})
	 */
	public function show(Event $event): Response
	{
		return $this->render('event/show.html.twig', [
			'event' => $event,
		]);
	}
	
	/**
	 * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
	 */
	public function edit(Request $request, Event $event): Response
	{
		$form = $this->createForm(EventType::class, $event);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			$this->entityManager->flush();
			return $this->redirectToRoute('event_index');
		}
		
		return $this->render('event/edit.html.twig', [
			'event' => $event,
			'form' => $form->createView(),
		]);
	}
	
	/**
	 * @Route("/{id}", name="delete", methods={"DELETE"})
	 */
	public function delete(Request $request, Event $event): Response
	{
		if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))) {
			$this->entityManager->remove($event);
			$this->entityManager->flush();
		}
		
		return $this->redirectToRoute('event_index');
	}
	
	/**
	 * @Route("/inscription/{id}", name="inscription", methods={"GET"})
	 */
	public function inscription(Event $event, EntityManagerInterface $userManager, Inscription $inscription): Response
	{
		$inscription->setUser($this->getUser());
		$inscription->setEvent($event);
		
		if (!$inscription->eventOpen()) {
			// si l'évènement n'est pas ouvert
			$this->addFlash("danger", "L'évènement n'est pas ouvert, votre inscription est refusée");
		} elseif (!$inscription->limitDate()) {
			// si la date d'inscription est dépassée
			$this->addFlash("danger", "La date limite d'inscription est dépassée, votre inscription est refusée");
		} else {
			// si tout est ok, on enregistre l'inscription
			$event->addUser($this->getUser());
			$userManager->persist($this->getUser());
			$userManager->flush();
		}
		
		return $this->render('event/show.html.twig', [
			'event' => $event,
		]);
	}
}
