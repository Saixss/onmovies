<?php

namespace App\Repository;

use App\Entity\Movie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findFavMovieById(string $movieId, string $userId)
    {

//        $rsm = new ResultSetMapping();
//
//        $queryBuilder = $this->getEntityManager()
//            ->createQueryBuilder()
//            ->select('user')
//            ->from('App:User', 'user')
//            ->innerJoin('user.favorite', 'favorite')
//            ->innerJoin('favorite.categories', 'categories')
//            ->where('favorite.id = :movieId')
//            ->andWhere('user.id = :userId')
//            ->setParameter('movieId', $movieId)
//            ->setParameter('userId', 32);
//
//        ->createNativeQuery('
//            SELECT movie_id
//            FROM user_movie
//            WHERE
//                user_movie.user_id = :userId
//            AND
//                user_movie.movie_id = :movieId
//        ', $rsm);
//
//        $queryBuilder->setParameters(new ArrayCollection([
//            new Parameter('userId', $userId),
//            new Parameter('movieId', $movieId)
//        ]));
//
//        return $queryBuilder->getResult();

        $entityManager = $this->getEntityManager();

        $qb = $entityManager->createQueryBuilder()
            ->select('movie')
            ->from(Movie::class, 'movie')
            ->innerJoin('movie.users', 'users')
            ->addSelect('users')
            ->where('users.id = :userId')
            ->andWhere('movie.id = :movieId')
            ->setParameter('userId', $userId)
            ->setParameter('movieId', $movieId);

        $query = $qb->getQuery();

        return $query->getResult();
    }

//    /**
//     * @return User[] Returns an array of User objects
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

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
