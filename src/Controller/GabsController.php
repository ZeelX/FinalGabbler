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
        $currentUser = $this->getUser();

        $gab = new Gabs();
        $gab->setAuthor($this->getUser());
        $gab->setCreatedAt(new \DateTime('now'));
        $form = $this->createForm(GabsType::class, $gab);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $gabsRepository->save($gab, true);

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gabs/new.html.twig', [
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
