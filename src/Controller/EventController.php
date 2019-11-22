<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\StatusEnum;
use App\Form\EventType;
use App\Repository\CityRepository;
use App\Repository\EventRepository;
use App\Repository\SchoolRepository;
use App\Services\Inscription;
use App\Services\Withdraw;
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
        $paginator = $eventRepository->findByFilters($value, $start, $end, $school, $page, $pastevents);
        return $this->render('event/manage.html.twig', [
            'paginator' => $paginator,
            'schools' => $schoolRepository->findAll(),
            'page' => $page
        ]);
    }

    /**
     * Cette route permet de créer une sortie :
     * - si la date de début de la sortie est supérieure à la date actuelle
     * - si le nombre maximum de participants est supérieur à 0
     * Alors :
     * - l'utilisateur en cours devient le créateur de l'annonce
     * - le statut de la sortie devient cree
     * @Route("/creer", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, CityRepository $cityRepository): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()
            && $event->getStart() > new \DateTime()
            && $event->getMaxsize() > 0) {
            //L'utilisateur connecté qui crée l'event devient le creator
            $event->setCreator($this->getUser());
            //le statut de la sortie devient cree
            $event->setStatus(StatusEnum::CREE);

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
     * Cette page permet d'affcher les détails d'une sortie en particulier :
     * @Route("/show/{id}", name="show", methods={"GET"})
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
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Event $event, CityRepository $cityRepository): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()
            && $this->getUser() == $event->getCreator()
            && $event->getStart() > new \DateTime()
            && ($event->getStatus() == StatusEnum::CREE || $event->getStatus() == StatusEnum::OUVERTE)) {

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
     * Cette route permet de supprimer une sortie :
     * (Une sortie publiée ne peut plus être supprimée mais doit être anullée)
     * - si l'utilisateur est bien le créateur
     * - si la date de début de la sortie est supérieure à la date actuelle
     * - si le statut de la sortie est : crée
     * @Route("/{id}", name="delete", methods={"DELETE"})
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
     * @Route("/cancel/{id}", name="cancel", methods={"GET"})
     */
    public function cancel(Event $event): Response
    {
        if ($this->getUser() == $event->getCreator()
            && $event->getStart() > new \DateTime()
            && $event->getCancel() != null
            && $event->getStatus() == StatusEnum::OUVERTE) {

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
     * Un utilisateur se désinscrit d'une sortie où il s'est inscrit
     * @Route("/sedésinscrire/{id}", name="withdraw", methods={"GET"})
     */
    public function withdraw(Event $event, Withdraw $withdraw): Response {
        $withdraw->setEvent($event);
        $withdraw->setUser($this->getUser());
        $withdraw->leave();
        $statut = $event->getStatus();
        dump($statut);

        return $this->render('event/show.html.twig', compact('event'));

    }

    /**
     * Cette route permet de publier une annonce :
     * (Une annonce est publiée après avoir été créée et non supprimée)
     * - si l'utilisateur est bien le créateur
     * - si la date de début de la sortie est supérieure à la date actuelle
     * - si le statut de la sortie est : crée
     * (Une annonce n'est publiée ne peut plus être supprimée mais doit être annulée)
     * @Route("/publish/{id}", name="publish", methods={"GET"})
     */
    public function publish(Event $event): Response
    {
        if($this->getUser() == $event->getCreator()
            && $event->getStart() > new \DateTime()
            && $event->getStatus() == StatusEnum::CREE) {

            $event->setStatus(StatusEnum::OUVERTE);
        }

        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

}
