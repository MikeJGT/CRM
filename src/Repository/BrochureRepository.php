<?php

namespace App\Repository;

use App\Entity\Brochure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Brochure>
 *
 * @method Brochure|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brochure|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brochure[]    findAll()
 * @method Brochure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrochureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brochure::class);
    }

//    /**
//     * @return Brochure[] Returns an array of Brochure objects
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

//    public function findOneBySomeField($value): ?Brochure
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
