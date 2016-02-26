<?php

namespace Inachis\Component\CoreBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Abstract class used to provide common functions to
 * EntityRepository classes
 */
abstract class AbstractManager extends EntityRepository
{
    /**
     * @var EntityManager Used for Repository interactions
     */
    protected $em;
    /**
     * Default consutrctor for AbstractManager
     * @param EntityManager Used for repository interactions
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * Implementations of AbstractManager must implement getClass
     * to indicate the name of the repository
     */
    abstract protected function getClass();
    /**
     * Returns an instance of the current entity
     */
    abstract public function create();
    /**
     * Uses the objects setters to populat the object
     * based on the provided values
     * @param mixed $object The object to hydrate
     * @param array[mixed] The values to apply to the obect
     * @return mixed The hydrated object
     */
    protected function hydrate($object, array $values)
    {
        foreach ($values as $key => $value) {
            $methodName = 'set' . ucfirst($key);
            if (method_exists($object, $methodName)) {
                $object->$methodName($value);
            }
        }
        return $object;
    }
    /**
     * Return the repository
     * @return The repsoitory to return
     */
    protected function getRepository()
    {
        return $this->em->getRepository($this->getClass());
    }
    /**
     * Fetches a specific entity from the repository by the given Id
     * @param string The Id of the entity to be returned
     * @return mixed The returned entity
     */
    public function getById($id)
    {
        return $this->getRepository()->find($id);
    }
    /**
     * Returns all entries for the current repository
     * @param int $limit The maximum number of results to return
     * @param int $offset The offset from which to return results from
     * @return array[mixed] The result of fetching the objects
     */
    public function getAll(
        $limit = -1,
        $offset = -1,
        $where = array(),
        $order = array()
    ) {
        return $this->getRepository()->findBy(
            $where,
            $order,
            $limit,
            $offset
        );
    }
}
