<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
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
	 * @Route("/", name="index", methods={"GET"})
	 */
	public function index(): Response
	{
		return $this->render('event/index.html.twig', [
			'events' => $this->eventRepository->findAll(),
		]);
	}
	
	/**
	 * @Route("/creer", name="new", methods={"GET","POST"})
	 */
	public function new(Request $request): Response
	{
		$event = new Event();
		$form = $this->createForm(EventType::class, $event);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			$this->entityManager->persist($event);
			$this->entityManager->flush();
			
			return $this->redirectToRoute('event_index');
		}
		
		return $this->render('event/new.html.twig', [
			'event' => $event,
			'form' => $form->createView(),
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
	 * @Route("/inscription", name="inscription", methods={"GET"})
	 */
	public function inscription(Request $request, Event $event): Response
	{
		
		$event = $this->eventRepository->find($this->getParameter("id"))->setStatus();
		
		return $this->render('event/show.html.twig');
	}
}
