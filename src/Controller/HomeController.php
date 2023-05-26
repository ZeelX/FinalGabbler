<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\GabsRepository;
use App\Repository\UserInteractionRepository;
use App\Repository\UserRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil')]
class HomeController extends AbstractController
{
    #[Route('/edit/{id}', name: 'app_edit_profil', methods: ['GET', 'POST'])]

        public function edit(Request $request, User $user, UserRepository $ur): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ur->save($user, true);

            return $this->redirectToRoute('app_edit_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('home/edit.html.twig', [
            'user' => $user,
            'profil' => $ur->findOneId($this->getUser()->getId()),
            'form' => $form,
        ]);

    }

    #[Route('/view/{id}', name: 'app_user_profil')]
    public function seeProfil(UserRepository $ur, GabsRepository $gr,UserInteractionRepository $ui, $id): Response
    {

        $friend = $ur->findAllLinkedUser($id);

        return $this->render('home/profilUser.html.twig', [
            'controller_name' => 'HomeController',
            'profil' => $ur->findOneId($id),
            'gabs' =>  $gr->findByAuthorId($id),
            'allfollowed' => $ui->findAllLinkedUser($id)

        ]);
    }

    #[Route('/shop', name: 'app_user_shop')]
    public function shop(UserRepository $ur): Response
    {

        return $this->render('home/shop.html.twig', [
            'controller_name' => 'HomeController',
            'profil' => $ur->findOneId($this->getUser()->getId()),


        ]);
    }

    #[Route('/shop_verify/{id}', name: 'app_user_shop_verify')]
    public function shopVerify(UserRepository $ur, User $user): Response
    {
            $user->setDateStartSubscription(date_create('now'));
            $user->setIsPremium(true);


        return $this->render('home/shopVerify.html.twig', [
            'controller_name' => 'HomeController',
            'profil' => $ur->findOneId($this->getUser()->getId()),


        ]);
    }

    #[Route('/{value}', name: 'app_profil')]
    public function index(UserRepository $ur, GabsRepository $gr,UserInteractionRepository $ui,$value): Response
    {
        $currentUserId= $this->getUser()->getId();
        $friend = $ur->findAllLinkedUser($currentUserId);


        if ($value == 1) {
            $gabs = $gr->findByAuthorId($this->getUser()->getId());
        }
        if ($value == 2) {
            $gabs = $gr->requestCreatedAtDesc($this->getUser()->getId());
        }
        if ($value == 3) {
            $gabs = $gr->requestLikeDesc($this->getUser()->getId());
        }
        if ($value == 4) {
            $gabs = $gr->requestLikeAsc($this->getUser()->getId());
        }
        if ($value == 5) {
            $gabs = $gr->requestDislikAsc($this->getUser()->getId());
        }
        if ($value == 6) {
            $gabs = $gr->requestDislikDesc($this->getUser()->getId());
        }

        return $this->render('home/profil.html.twig', [
            'me' => $currentUserId,
            'controller_name' => 'HomeController',
            'profil' => $ur->findOneId($currentUserId),
            'gabs' =>  $gabs,
            'allfollowed' => $friend
        ]);
    }
}
