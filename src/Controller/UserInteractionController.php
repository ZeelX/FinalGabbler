<?php

namespace App\Controller;

use App\Entity\UserInteraction;
use App\Repository\GabsRepository;
use App\Repository\UserInteractionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]

class UserInteractionController extends AbstractController
{

    #[Route('/new_interaction/{id}/{value}', name: 'app_user_interaction')]
    public function newLike(Request $request,UserRepository $user, UserInteractionRepository $uir, $id, $value, EntityManagerInterface $em): Response
    {

        $objectSearched = $uir->findOneBySomeField($this->getUser()->getId(), $user->findOneId($id));
        if ($objectSearched == true) {
            $em->remove($objectSearched);
            $em->flush();
        }

        $interaction = new UserInteraction();
        $interaction->setListOwner($this->getUser());
        $interaction->setRelatedUser($user->findOneId($id));
        $interaction->setValue($value);
        $uir->save($interaction, true);
        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);

    }

}
