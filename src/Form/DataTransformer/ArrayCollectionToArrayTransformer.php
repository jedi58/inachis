<?php

namespace App\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class ArrayCollectionToArrayTransformer
 * @package App\Form\DataTransformer
 */
class ArrayCollectionToArrayTransformer implements DataTransformerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ArrayCollectionToArrayTransformer constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function transform($value)
    {
        return !empty($value) ? $value->toArray() : [];
    }

    /**
     * @param mixed $value
     * @return ArrayCollection|mixed
     */
    public function reverseTransform($value)
    {
        return new ArrayCollection($value);
    }
}
