<?php
 /*
  * This file is part of the Axiomadev project.
  *
  * (c) Yury Smidovich 11/06/14
  */

namespace Axioma\TagBundle\EntityManager;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Class TagManager
 *
 * @author Yury Smidovich <dev@stmol.me>
 */
class TagManager
{
    /**
     * @var EntityManager
     */
    private $entityManger;

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
        $this->entityManger = $entityManager;
        $this->repository = $entityManager->getRepository($className);
    }

    /**
     * @param int $limit
     * @param null $offset
     *
     * @return array
     */
    public function findTagsLimited($limit = 50, $offset = null)
    {
        $queryBuilder = $this->repository
            ->createQueryBuilder('t')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}
 