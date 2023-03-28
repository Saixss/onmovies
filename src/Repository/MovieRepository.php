<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 *
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function save(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByPage(int $pageFirstResult, int $resultsPerPage, string $sort, string $order): Paginator
    {
        $queryBuilder = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('m')
            ->from('App:Movie', 'm')
            ->orderBy('m.' . $sort, $order)
            ->setFirstResult($pageFirstResult)
            ->setMaxResults($resultsPerPage);

        $query = $queryBuilder->getQuery();

        $paginator = new Paginator($query, $fetchJoinCollection = true);
        return  $paginator;
    }

    public function getByCategoryName(string $categoryName, int $pageFirstResult, int $resultsPerPage, string $sort, string $order): Paginator
    {
        $queryBuilder = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('m')
            ->from('App:Movie', 'm')
            ->innerJoin('m.categories', 'c')
            ->where('c.name = :name')
            ->setParameter('name', $categoryName)
            ->orderBy('m.' . $sort, $order)
            ->setFirstResult($pageFirstResult)
            ->setMaxResults($resultsPerPage);

        $query = $queryBuilder->getQuery();

        $paginator = new Paginator($query);
        return $paginator;
    }

//    /**
//     * @return Movie[] Returns an array of Movie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Movie
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
