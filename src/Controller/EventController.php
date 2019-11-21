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
        // TODO : Lorsqu'un user affiche la page la 1re fois, la school par défaut est la sienne
        /*if ($request->isMethod('get')) {
            $user = $this->getUser();
            dump($user);
           $school = $user->getSchool;

        } else {
            $school = $request->request->get('school');
        }*/
        // TODO : filtrer par school
        $school = $request->request->get('school');
        $paginator = $eventRepository->findByFilters($value, $start, $end, $school, $page);
        dump($school);
        return $this->render('event/manage.html.twig', [
            'paginator' => $paginator,
            'schools' => $schoolRepository->findAll(),
            'page' => $page

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
            //L'utilisateur connecté qui crée l'event devient le creator
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
     * @Route("/show/{id}", name="show", methods={"GET"})
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
    public function edit(Request $request, Event $event, CityRepository $cityRepository): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Modification de la sortie ' . $event->getName() . ' effectuée');
            $this->entityManager->flush();
            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
            'cities' => $cityRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))
            && $this->getUser() == $event->getCreator()
            && $event->getStart() > new \DateTime()
            && ($event->getStatus() == StatusEnum::CREE))
        {

            $this->entityManager->remove($event);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('event_index');
    }

    /**
     * @Route("/cancel/{id}", name="cancel", methods={"GET"})
     */
    public function cancel(Event $event): Response
    {
        if ($this->getUser() == $event->getCreator()
            && $event->getStart() > new \DateTime()
            && $event->getCancel() != null
        && $event->getStatus() == StatusEnum::OUVERTE)
        {
            $event->setStatus(StatusEnum::ANNULEE);
        }

        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * Inscription d'un utilisateur à une sortie
     *
     * @Route("/inscription/{id}", name="inscription", methods={"GET"})
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
     * @Route("/publish/{id}", name="publish", methods={"GET"})
     */
    public function publish(Event $event): Response
    {
        $event->setStatus(StatusEnum::OUVERTE);

        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

}
