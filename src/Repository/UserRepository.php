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

    public function findFavoritesByUserId(int $userId, int $resultStart)
    {
        $entityManager = $this->getEntityManager();

        $qb = $entityManager->createQueryBuilder()
            ->select('m')
            ->from('App:Movie', 'm')
            ->innerJoin('m.users', 'u')
            ->where('u.id = :id')
            ->setMaxResults(10)
            ->setFirstResult($resultStart)
            ->setParameter('id', $userId);

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
