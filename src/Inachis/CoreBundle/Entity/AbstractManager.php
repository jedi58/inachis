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
     * @var Reference to an encryption object
     */
    protected $encryptor;
    /**
     * Default constructor for AbstractManager
     * @param EntityManager $em Used for repository interactions
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->encryptor = null;
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
     * Return the repository
     * @return The repsoitory to return
     */
    protected function getRepository()
    {
        return $this->em->getRepository($this->getClass());
    }
    /**
     * Uses the objects setters to populat the object
     * based on the provided values
     * @param mixed $object The object to hydrate
     * @param array[mixed] The values to apply to the obect
     * @return mixed The hydrated object
     */
    protected function hydrate($object, array $values)
    {
        if (!is_object($object)) {
            return $object;
        }
        foreach ($values as $key => $value) {
            $methodName = 'set' . ucfirst($key);
            if (method_exists($object, $methodName)) {
                $object->$methodName($value);
            }
        }
        return $object;
    }
    /**
     * Encrypts the specified fields for the provided object
     * @param mixed $object The object to encrypt values in
     */
    protected function encryptFields($object)
    {
        if (method_exists($this, 'getEncryptedFields') && $object !== null) {
            $fields = $this->getEncryptedFields();
            foreach ($fields as $field) {
                $field = ucfirst($field);
                $getField = 'get' . $field;
                $setField = 'set' . $field;
                $object->$setField($this->encryptor->encrypt($object->$getField()));
            }
        }
    }
    /**
     * Decrypts the specified fields for the provided object
     * @param mixed $object The object to decrypt values in
     */
    protected function decryptFields($object)
    {
        if (method_exists($this, 'getEncryptedFields') && $object !== null) {
            $fields = $this->getEncryptedFields();
            foreach ($fields as $field) {
                $field = ucfirst($field);
                $getField = 'get' . $field;
                $setField = 'set' . $field;
                $object->$setField($this->encryptor->decrypt($object->$getField()));
            }
        }
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
     * @param int $offset The offset from which to return results from
     * @param int $limit The maximum number of results to return
     * @param array $where
     * @param array $order
     * @return array [mixed] The result of fetching the objects
     */
    public function getAll(
        $offset = 0,
        $limit = 25,
        $where = array(),
        $order = array()
    ) {
        $qb = $this->getRepository()->createQueryBuilder('q');
        if (!empty($where)) {
            $qb = $qb->where($where[0]);
        }
        if (!empty($order)) {
            $qb = $qb->orderBy($order);
        }
        if (!empty($where)) {
            $qb = $qb->setParameters($where[1]);
        }
        $qb = $qb->getQuery();
        if ($offset > 0) {
            $qb = $qb->setFirstResult($offset);
        }
        if ($limit > 0) {
            $qb = $qb->setMaxResults($limit);
        }
        return $qb->getResult();
    }

    /**
     * Returns the count for entries in the current repository match any
     * provided constraints
     * @param string[] Array of elements and string replacements
     * @return int The number of entities located
     */
    public function getAllCount($where = array())
    {
        $qb = $this->getRepository()->createQueryBuilder('q')
            ->select('count(q.id)');
        if (!empty($where)) {
            $qb->where($where[0]);
            if (isset($where[1])) {
                $qb->setParameters($where[1]);
            }
        }
        return (int) $qb
            ->getQuery()
            ->getSingleScalarResult();
    }
    /**
     * Flushes the entity manager
     */
    public function flush()
    {
        $this->em->flush();
    }
}
