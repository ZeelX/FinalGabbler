<?php

namespace App\Controller;

use App\Repository\GabsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_profil')]
    public function index(UserRepository $ur, GabsRepository $gr): Response
    {

        return $this->render('home/profil.html.twig', [
            'controller_name' => 'HomeController',
            'profil' => $ur->findOneId($this->getUser()->getId()),
            'gabs' =>  $gr->findByAuthorId($this->getUser()->getId())
        ]);
    }

    #[Route('/profil/{id}', name: 'app_user_profil')]
    public function seeProfil(UserRepository $ur, GabsRepository $gr, $id): Response
    {

        return $this->render('home/profilUser.html.twig', [
            'controller_name' => 'HomeController',
            'profil' => $ur->findOneId($id),
            'gabs' =>  $gr->findByAuthorId($id)

        ]);
    }
}
