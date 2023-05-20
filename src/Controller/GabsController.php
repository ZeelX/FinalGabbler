<?php

namespace App\Controller;

use App\Entity\Gabs;
use App\Form\GabsType;
use App\Repository\GabsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class GabsController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(GabsRepository $gabsRepository): Response
    {
        return $this->render('gabs/index.html.twig', [
            'gabs' => $gabsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_gabs_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GabsRepository $gabsRepository): Response
    {
        $gab = new Gabs();
        $form = $this->createForm(GabsType::class, $gab);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gabsRepository->save($gab, true);

            return $this->redirectToRoute('app_gabs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gabs/new.html.twig', [
            'gab' => $gab,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gabs_show', methods: ['GET'])]
    public function show(Gabs $gab): Response
    {
        return $this->render('gabs/show.html.twig', [
            'gab' => $gab,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gabs_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gabs $gab, GabsRepository $gabsRepository): Response
    {
        $form = $this->createForm(GabsType::class, $gab);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gabsRepository->save($gab, true);

            return $this->redirectToRoute('app_gabs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gabs/edit.html.twig', [
            'gab' => $gab,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gabs_delete', methods: ['POST'])]
    public function delete(Request $request, Gabs $gab, GabsRepository $gabsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gab->getId(), $request->request->get('_token'))) {
            $gabsRepository->remove($gab, true);
        }

        return $this->redirectToRoute('app_gabs_index', [], Response::HTTP_SEE_OTHER);
    }
}
