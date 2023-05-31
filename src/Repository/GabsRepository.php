<?php

namespace App\Repository;

use App\Entity\Gabs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gabs>
 *
 * @method Gabs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gabs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gabs[]    findAll()
 * @method Gabs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GabsRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gabs::class);
    }

    public function save(Gabs $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Gabs $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //fonction de tri

    /**
     * @return Gabs[] Returns an array of Gabs objects
     */

    public function findByAuthorId($value): array
    {

            return $this->createQueryBuilder('g')
                ->join(
                    'App\Entity\User',
                    'u',
                    'ON g.author = u.id',
                )
                ->where('g.author = u.id')
                ->join(
                    'App\Entity\UserInteraction',
                    'ui',
                    'ON u.id = ui.relatedUser',
                )
                ->andWhere('u.id = ui.relatedUser')
                ->andWhere('ui.listOwner = :val')
                ->setParameter('val', $value)
                ->orderBy('g.createdAt', 'desc')
                ->getQuery()
                ->getResult();
        }

    public function requestCreatedAtDesc($value): array
    {

           return $this->createQueryBuilder('g')
               ->join(
                   'App\Entity\User',
                   'u',
                   'ON g.author = u.id',
               )
               ->where('g.author = u.id')
               ->join(
                   'App\Entity\UserInteraction',
                   'ui',
                   'ON u.id = ui.relatedUser',
               )
               ->andWhere('u.id = ui.relatedUser')
               ->andWhere('ui.listOwner = :val')
               ->setParameter('val', $value)
               ->orderBy('g.createdAt', 'asc')
               ->getQuery()
               ->getResult();
    }

//    public function requestLikeAsc($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->join(
//                'App\Entity\User',
//                'u',
//                'ON g.author = u.id',
//            )
//            ->where('g.author = u.id')
//            ->join(
//                'App\Entity\UserInteraction',
//                'ui',
//                'ON u.id = ui.relatedUser',
//            )
//            ->andWhere('u.id = ui.relatedUser')
//            ->join(
//                'App\Entity\UserLike',
//                'ul',
//                'ON u.id = ul.user',
//            )
//            ->andWhere('u.id = ul.user')
//            ->andWhere('ui.listOwner = :val')
//            ->setParameter('val', $value)
//            ->orderBy('count(ul.value)', 'asc')
//            ->getQuery()
//            ->getResult();
//
//    }
//
//    public function requestLikeDesc($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->join(
//                'App\Entity\User',
//                'u',
//                'ON g.author = u.id',
//            )
//            ->where('g.author = u.id')
//            ->join(
//                'App\Entity\UserInteraction',
//                'ui',
//                'ON u.id = ui.relatedUser',
//            )
//            ->andWhere('u.id = ui.relatedUser')
//            ->join(
//                'App\Entity\UserLike',
//                'ul',
//                'ON u.id = ul.user',
//            )
//            ->andWhere('u.id = ul.user')
//            ->andWhere('ui.listOwner = :val')
//            ->setParameter('val', $value)
//            ->orderBy('count(ul.value)', 'asc')
//            ->getQuery()
//            ->getResult();
//    }

//    public function requestDislikAsc($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->join(
//                'App\Entity\User',
//                'u',
//                'ON g.author = u.id',
//            )
//            ->where('g.author = u.id')
//            ->join(
//                'App\Entity\UserInteraction',
//                'ui',
//                'ON u.id = ui.relatedUser',
//            )
//            ->andWhere('u.id = ui.relatedUser')
//            ->join(
//                'App\Entity\UserLike',
//                'ul',
//                'ON u.id = ui.relatedUser',
//            )
//            ->andWhere('u.id = ul.relatedUser')
//            ->andWhere('ui.listOwner = :val')
//            ->setParameter('val', $value)
//            ->orderBy('count(ul.value)', 'asc')
//            ->getQuery()
//            ->getResult();
//    }
//
//    public function requestDislikDesc($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->join(
//                'App\Entity\User',
//                'u',
//                'ON g.author = u.id',
//            )
//            ->where('g.author = u.id')
//            ->join(
//                'App\Entity\UserInteraction',
//                'ui',
//                'ON u.id = ui.relatedUser',
//            )
//            ->andWhere('u.id = ui.relatedUser')
//            ->join(
//                'App\Entity\UserLike',
//                'ul',
//                'ON u.id = ui.relatedUser',
//            )
//            ->andWhere('u.id = ul.relatedUser')
//            ->andWhere('ui.listOwner = :val')
//            ->setParameter('val', $value)
//            ->orderBy('count(ul.value)', 'desc')
//            ->getQuery()
//            ->getResult();
//    }



    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneById($value): ?Gabs
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
