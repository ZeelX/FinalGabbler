<?php

namespace App\Repository;

use App\Entity\Gabs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
            ->andWhere('g.author = :val')
            ->setParameter('val', $value)
            ->orderBy('g.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function requestCreatedAtDesc($value): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.author = :val')
            ->setParameter('val', $value)
            ->orderBy('g.createdAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function requestLikeAsc($value): array
    {
        return $this->createQueryBuilder('g')
            ->join(
                'App\Entity\UserLike' ,
                'ul',
                'g.id = ul.gabs_ref_id')
            ->where('g.author = :valOne')
            ->andWhere('ul.value = :valTwo')
            ->setParameters(['valOne' => $value, 'valTwo' => 1])
            ->groupBy('g.id','ul.value')
            ->orderBy('count(ul.value)', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function requestLikeDesc($value): array
    {
        return $this->createQueryBuilder('g')
            ->join(
                'App\Entity\UserLike' ,
                'ul',
                'g.id = ul.gabs_ref_id')
            ->where('g.author = :valOne')
            ->andWhere('ul.value = :valTwo')
            ->setParameters(['valOne' => $value, 'valTwo' => 1])
            ->groupBy('g.id','ul.value')
            ->orderBy('count(ul.value)', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function requestDislikAsc($value): array
    {
        return $this->createQueryBuilder('g')
            ->join(
                'App\Entity\UserLike' ,
                'ul',
                'g.id = ul.gabs_ref_id')
            ->where('g.author = :valOne')
            ->andWhere('ul.value = :valTwo')
            ->setParameters(['valOne' => $value, 'valTwo' => 2])
            ->groupBy('g.id','ul.value')
            ->orderBy('count(ul.value)', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function requestDislikDesc($value): array
    {
        return $this->createQueryBuilder('g')
            ->join(
                'App\Entity\UserLike' ,
                'ul',
                'g.id = ul.gabs_ref_id')
            ->where('g.author = :valOne')
            ->andWhere('ul.value = :valTwo')
            ->setParameters(['valOne' => $value, 'valTwo' => 2])
            ->groupBy('g.id','ul.value')
            ->orderBy('count(ul.value)', 'desc')
            ->getQuery()
            ->getResult()
        ;
    }


    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneById($value): ?Gabs
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
