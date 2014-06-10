<?php
 /*
  * This file is part of the Axiomadev project.
  *
  * (c) Yury Smidovich 09/06/14
  */

namespace Axioma\MovieBundle\EntityManager;

use Axioma\MovieBundle\Entity\Movie;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Class MovieManager
 *
 */
class MovieManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @param EntityManager $entityManager
     * @param $className
     */
    public function __construct(EntityManager $entityManager, $className)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository($className);
    }

    /**
     * @return Movie
     */
    public function createMovie()
    {
        return new Movie();
    }

    /**
     * @param Movie $movie
     * @param bool $flush
     */
    public function saveMovie(Movie $movie, $flush = true)
    {
        $this->entityManager->persist($movie);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * @param Movie $movie
     * @param bool $flush
     */
    public function deleteMovie(Movie $movie, $flush = true)
    {
        $this->entityManager->remove($movie);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * @param int $limit
     * @param null $offset
     *
     * @return array
     */
    public function findMoviesLimited($limit = 50, $offset = null)
    {
        $queryBuilder = $this->repository
            ->createQueryBuilder('m')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $id
     *
     * @return null|object
     */
    public function findMovieById($id)
    {
        return $this->repository->find($id);
    }
}
 