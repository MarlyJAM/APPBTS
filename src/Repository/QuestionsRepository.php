<?php

namespace App\Repository;

use App\Entity\Questions;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;


/**
 * @extends ServiceEntityRepository<Questions>
 *
 * @method Questions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Questions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Questions[]    findAll()
 * @method Questions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionsRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private PaginatorInterface $paginatorInterface
    ) {
        parent::__construct($registry, Questions::class);
    }


    public function save(Questions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Questions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Questions[] Returns an array of Questions objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Questions
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

     public function findBySearch(SearchData $searchData ): PaginationInterface
     {
        $state=true;
       $data= $this->createQueryBuilder('q')
                        ->where('q.isVerified LIKE :isVerified')
                        ->setParameter('isVerified' ,$state)
                        ->addOrderBy('q.createdAt', 'DESC');;

        if (!empty($searchData->p)) {
            $data=$data
                ->andWhere('q.mainTitle LIKE  :p')
                ->setParameter('p',"%{$searchData->p}%");
        }
        $data=$data 
                  ->getQuery()
                  ->getResult();

        $questions= $this->paginatorInterface->paginate($data, $searchData->page ,9);

        return $questions;
     }
     /*public function findisVerified( ): PaginationInterface
     {
        $state=true;
        return $this->createQueryBuilder('q')
            ->andWhere('q.isVerified LIKE :isVerified')
            ->setParameter('isVerified' ,$state)
            ->orderBy('q.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
     }*/

}
