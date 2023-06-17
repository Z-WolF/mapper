<?php

namespace App\Repository;

use App\Entity\Feature;
use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Feature>
 *
 * @method Feature|null find($id, $lockMode = null, $lockVersion = null)
 * @method Feature|null findOneBy(array $criteria, array $orderBy = null)
 * @method Feature[]    findAll()
 * @method Feature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Feature::class);
    }

    public function save(Feature $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Feature $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByGameEnabled(Game $game)
    {
        return $this->createQueryBuilder('f')
            ->addSelect('fc')
            ->join('f.category', 'fc')
            ->andWhere('fc.game = :game')
            ->andWhere('fc.enabled = true')
            ->andWhere('f.enabled = true')
            ->setParameter('game', $game)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Feature[] Returns an array of Feature objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Feature
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
