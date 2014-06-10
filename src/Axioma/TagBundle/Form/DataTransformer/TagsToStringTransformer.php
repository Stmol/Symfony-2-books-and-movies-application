<?php
 /*
  * This file is part of the Axiomadev project.
  *
  * (c) Yury Smidovich 11/06/14
  */

namespace Axioma\TagBundle\Form\DataTransformer;


use Axioma\TagBundle\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class TagsToStringTransformer
 *
 * @author Yury Smidovich <dev@stmol.me>
 */
class TagsToStringTransformer implements DataTransformerInterface
{
    /**
     * Delimiter for tags string
     */
    const TAGS_DELIMITER = ',';

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
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository('AxiomaTagBundle:Tag');
    }

    /**
     * Transforms an object (Tag) to a string (name).
     *
     * @param ArrayCollection|null $tags
     *
     * @return string
     */
    public function transform($tags)
    {
        if (null === $tags) {
            return '';
        }

        $names = [];
        $iterator = $tags->getIterator();

        while ($iterator->valid()) {
            $names[] = $iterator->current()->getName();
            $iterator->next();
        }

        return implode(self::TAGS_DELIMITER, $names);
    }

    /**
     * Transforms a string (names) to an array of objects (Tags).
     *
     * @param string $names
     *
     * @return ArrayCollection
     */
    public function reverseTransform($names)
    {
        if (!$names) {
            return new ArrayCollection();
        }

        $names = mb_strtolower($names);

        $namesArray = explode(self::TAGS_DELIMITER, $names);
        $namesArray = array_map('trim', $namesArray);
        $namesArray = array_filter($namesArray);

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->repository
            ->createQueryBuilder('t');

        $tagsExisting = $queryBuilder
            ->where($queryBuilder->expr()->in('t.name', $namesArray))
            ->getQuery()
            ->getResult();

        $tags = new ArrayCollection($tagsExisting);

        foreach ($namesArray as $name) {
            $isExistInTags = $tags->exists(function ($key, $el) use ($name) {
                    return $el->getName() == $name;
                });

            if (!$isExistInTags) {
                $newTag = new Tag();
                $newTag->setName($name);

                $tags->add($newTag);
            }
        }

        return $tags;
    }
}
 