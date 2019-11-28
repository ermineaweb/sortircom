<?php

namespace App\Controller;

use App\Entity\Place;
use App\Form\PlaceType;
use App\Repository\PlaceRepository;
use App\Technical\Alert;
use App\Technical\Messages;
use http\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/place")
 */
class PlaceController extends AbstractController
{
    /**
     * @Route("/", name="place_index", methods={"GET"})
     */
    public function index(PlaceRepository $placeRepository): Response
    {
        return $this->render('place/index.html.twig', [
            'places' => $placeRepository->findAll(),
        ]);
    }
    
    /**
     * @Route("/api", name="api_place_index", methods={"GET"})
     */
    public function indexApi(PlaceRepository $placeRepository): Response
    {
        return $this->json($placeRepository->findAll());
    }

    /**
     * @Route("/new", name="place_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $place = new Place();
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($place);
            $entityManager->flush();
            $this->addFlash(Alert::SUCCESS, Messages::PLACE_SUCESS_NEW);
            return $this->redirectToRoute('place_index');
        }

        return $this->render('place/new.html.twig', [
            'place' => $place,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="place_show", methods={"GET"})
     */
    public function show(Place $place): Response
    {
        return $this->render('place/show.html.twig', [
            'place' => $place,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="place_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Place $place): Response
    {
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(Alert::SUCCESS, Messages::PLACE_SUCESS_EDIT);

            return $this->redirectToRoute('place_index');
        }

        return $this->render('place/edit.html.twig', [
            'place' => $place,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="place_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Place $place): Response
    {
        if ($this->isCsrfTokenValid('delete'.$place->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($place);
            $entityManager->flush();
            $this->addFlash(Alert::SUCCESS, Messages::PLACE_SUCESS_DELETE);
        }

        return $this->redirectToRoute('place_index');
    }
}
