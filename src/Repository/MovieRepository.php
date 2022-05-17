<?php

namespace App\Repository;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Movie $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Movie $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    public function findByOptions(Movie $movieSearch)
    {
        // dd($movieSearch->getGenre());

        $query = $this->createQueryBuilder('m');
        if ($movieSearch->getGenreIds()) {
            $i = 0;
            foreach ($movieSearch->getGenreIds() as $option) {
                $query = $query->andWhere(":valGenre$i MEMBER OF m.genre_ids")->setParameter("valGenre$i", $option);
                $i++;
            }
        }
        if ($movieSearch->getTitle()) {
            $query = $query->andWhere("m.title LIKE :valTitle")->setParameter("valTitle", '%'.$movieSearch->getTitle().'%');
        }

        // :val MEMBER OF c.table si utilisation d'un tableau de collections

        return $query->getQuery()->getResult();
    }

    /**
     * @return Movie[] Returns an array of Movie objects
     */
    public function findByGenres(Genre $favGenre)
    {
        $query = $this->createQueryBuilder('m');
  
            $i = 0;
            foreach ($favGenre as $option) {
                $query = $query->andWhere(":valGenre$i MEMBER OF m.genre_ids")->setParameter("valGenre$i", $option);
                $i++;
            }
        
        return $query->setMaxResults(3)->getQuery()->getResult();
 
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Movie
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
