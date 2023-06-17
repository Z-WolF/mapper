<?php

namespace App\Repository;

use App\Entity\BaseLayer;
use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BaseLayer>
 *
 * @method BaseLayer|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseLayer|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseLayer[]    findAll()
 * @method BaseLayer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseLayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseLayer::class);
    }

    public function save(BaseLayer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BaseLayer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByGameEnabledOrdered(Game $game)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.game = :game')
            ->andWhere('b.enabled = true')
            ->orderBy('b.position')
            ->setParameter('game', $game)
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return BaseLayer[] Returns an array of BaseLayer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BaseLayer
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
