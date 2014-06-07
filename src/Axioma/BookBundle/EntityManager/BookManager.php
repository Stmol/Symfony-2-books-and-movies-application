<?php

namespace Axioma\BookBundle\EntityManager;

use Axioma\BookBundle\Entity\Book;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Class BookManager
 *
 * @author Yury Smidovich <dev@stmol.me>
 */
class BookManager
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
     * Create a Book
     *
     * @return Book
     */
    public function createBook()
    {
        return new Book();
    }

    /**
     * Persist Book
     *
     * @param Book $book
     * @param bool $flush
     */
    public function saveBook(Book $book, $flush = true)
    {
        $this->entityManager->persist($book);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * Remove Book
     *
     * @param Book $book
     * @param bool $flush
     */
    public function deleteBook(Book $book, $flush = true)
    {
        $this->entityManager->remove($book);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * Find all books limited
     *
     * @param int|null $limit
     * @param null $offset
     *
     * @return ArrayCollection
     */
    public function findBooksLimited($limit = 50, $offset = null)
    {
        $queryBuilder = $this->repository
            ->createQueryBuilder('b')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        return $queryBuilder
            ->getQuery()
            ->execute();
    }

    /**
     * @param $id
     *
     * @return null|object
     */
    public function findBookById($id)
    {
        return $this->repository->find($id);
    }
}
 