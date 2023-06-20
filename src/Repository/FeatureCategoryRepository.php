<?php

namespace App\Repository;

use App\Entity\FeatureCategory;
use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FeatureCategory>
 *
 * @method FeatureCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeatureCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeatureCategory[]    findAll()
 * @method FeatureCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeatureCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeatureCategory::class);
    }

    public function save(FeatureCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FeatureCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByGameEnabledOrderedRoot(Game $game)
    {
        return $this->createQueryBuilder('fc')
            ->addSelect('fcc')
            ->join('fc.children', 'fcc')
            ->andWhere('fc.game = :game')
            ->andWhere('fc.enabled = true')
            ->andWhere('fcc.enabled = true')
            ->andWhere('fc.parent IS NULL')
            ->addOrderBy('fc.position')
            ->addOrderBy('fcc.position')
            ->setParameter('game', $game)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Category[] Returns an array of Category objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Category
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
