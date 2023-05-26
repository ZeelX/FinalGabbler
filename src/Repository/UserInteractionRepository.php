<?php

namespace App\Repository;

use App\Entity\UserInteraction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserInteraction>
 *
 * @method UserInteraction|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserInteraction|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserInteraction[]    findAll()
 * @method UserInteraction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserInteractionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserInteraction::class);
    }

    public function save(UserInteraction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserInteraction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function findAllLinkedUser($idOwner) : array
    {
        return $this->createQueryBuilder('ui')
            ->join(
                'App\Entity\User' ,
                'u',
                'g.id =u.id')
            ->where('ui.listOwner = :val')
            ->setParameter('val', $idOwner)
            ->getQuery()
            ->getResult()
        ;
    }










    public function getLinkedName($idRelated){

    return $this->createQueryBuilder('ui')
        ->select('u.name')
        ->where('ui.listOwner = :val')
        ->setParameter('val', $idRelated)
        ->getQuery()
        ->getResult()
        ;
}

//    /**
//     * @return UserInteraction[] Returns an array of UserInteraction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findOneBySomeField($ownerId, $relatedUser): ?UserInteraction
    {
        return $this->createQueryBuilder('u')
            ->where('u.listOwner = :valOne')
            ->andWhere('u.relatedUser = :valTwo')
            ->setParameters(['valOne' => $ownerId, 'valTwo' => $relatedUser])
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
