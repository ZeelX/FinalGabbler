<?php

namespace App\Controller;

use App\Entity\Gabs;
use App\Entity\UserLike;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GabsRepository;
use App\Repository\UserLikeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    #[Route('/newLike/{id}/{value}', name: 'app_new_like', methods: ['GET'])]
    public function newLike(Request $request, Gabs $g, GabsRepository $gr, UserLikeRepository $ur, EntityManagerInterface $em, array $_route_params): Response

    {
        $currentGabs = $gr->findOneById($_route_params['id']);
        $objectSearched = $ur->findOneByIds($_route_params['id'], $this->getUser()->getId());
        if ($objectSearched == true) {
            $em->remove($objectSearched);
            $em->flush();

        }

        $like = new UserLike();
        $like->setUser($this->getUser());
        $like->setGabsRef($currentGabs);
        $like->setValue($_route_params['value']);
        $ur->save($like, true);

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);

    }

}