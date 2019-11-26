<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityType;
use App\Repository\CityRepository;
use App\Technical\Alert;
use App\Technical\Messages;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ville", name="city_")
 */
class CityController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(CityRepository $cityRepository, Request $request, $search = null): Response
    {
        $value = $request->query->get('search');
        if ($value) {
            $cities = $cityRepository->findByName($value);
        } else {
            $cities = $cityRepository->findAll();
        }

        return $this->render('city/manage.html.twig', [
            'cities' => $cities,
        ]);
    }

    /**
     * @Route("/", name="index", methods={"GET","POST"})
     */
    public function new(
        Request $request,
        CityRepository $cityRepository,
        EntityManagerInterface $entityManager,
        $search = null): Response
    {
        // TODO : refactoriser le code dans un service de recherche
        $value = $request->query->get('search');
        if (!$value == null) {
            $cities = $cityRepository->findByName($value);
        } else {
            $cities = $cityRepository->findAll();
        }

        $city = new City();
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($city);
            $entityManager->flush();
            $this->addFlash(Alert::SUCCESS, Messages::CITY_SUCESS_NEW);
            return $this->redirectToRoute('city_index');
        }

        return $this->render('city/manage.html.twig', [
            'city' => $city,
            'form' => $form->createView(),
            'cities' => $cities,
            'request' => $request,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(City $city): Response
    {
        return $this->render('city/show.html.twig', [
            'city' => $city,
        ]);
    }

    /**
     * @Route("/{id}/modifier", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, City $city): Response
    {
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash(Alert::SUCCESS, Messages::CITY_SUCESS_EDIT);

            return $this->redirectToRoute('city_show', ['id' => $city->getId()]);
        }

        return $this->render('city/edit.html.twig', [
            'city' => $city,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, City $city): Response
    {
        if ($this->isCsrfTokenValid('delete' . $city->getId(), $request->request->get('_token'))) {
            $entityManager->remove($city);
            $entityManager->flush();
            $this->addFlash(Alert::SUCCESS, Messages::CITY_SUCESS_DELETE);
        }

        return $this->redirectToRoute('city_index');
    }
}
