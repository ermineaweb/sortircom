<?php

namespace App\Controller;

use App\Entity\School;
use App\Form\SchoolType;
use App\Repository\SchoolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/school")
 */
class SchoolController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Cette route permet d'afficher la liste des écoles existantes
     * et propose d'en ajouter une
     * @Route("/", name="school_index", methods={"GET","POST"})
     */
    public function new(Request $request, SchoolRepository $schoolRepository): Response
    {
        $school = new School();
        $form = $this->createForm(SchoolType::class, $school);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($school);
            $this->entityManager->flush();

            return $this->redirectToRoute('school_index');
        }

        return $this->render('school/manage.html.twig', [
            'school' => $school,
            'form' => $form->createView(),
            'schools' => $schoolRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="school_show", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function show(School $school): Response
    {
        return $this->render('school/show.html.twig', [
            'school' => $school,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="school_edit", methods={"GET","POST"}, requirements={"id":"\d+"})
     */
    public function edit(Request $request, School $school): Response
    {
        $form = $this->createForm(SchoolType::class, $school);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('school_index');
        }

        return $this->render('school/edit.html.twig', [
            'school' => $school,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="school_delete", methods={"DELETE"}, requirements={"id":"\d+"})
     */
    public function delete(Request $request, School $school): Response
    {
        if ($this->isCsrfTokenValid('delete'.$school->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($school);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('school_index');
    }
}
