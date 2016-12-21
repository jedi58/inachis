<?php

namespace Inachis\Component\CoreBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

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
     * @var \Inachis\Component\CoreBundle\Security\Encryption Reference to an encryption object
     */
    protected $encryptor;
    /**
     * Default constructor for AbstractManager
     * @param EntityManager $entityManager Used for repository interactions
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
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
     * @return \Doctrine\ORM\EntityRepository The repository to return
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
    public function hydrate($object, array $values)
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
        $this->fieldEncryption('encrypt', $object);
    }
    /**
     * Decrypts the specified fields for the provided object
     * @param mixed $object The object to decrypt values in
     */
    protected function decryptFields($object)
    {
        $this->fieldEncryption('decrypt', $object);
    }

    /**
     * Encrypts or decrypts data dependent upon what function name is passed in
     * @param string $method Should contain either "encrypt" or "decrypt"
     * @param mixed $object The object to decrypt fields for
     */
    private function fieldEncryption($method, $object)
    {
        if (method_exists($this, 'getEncryptedFields') && $object !== null) {
            $fields = $this->getEncryptedFields();
            foreach ($fields as $field) {
                $field = ucfirst($field);
                $getField = 'get' . $field;
                $setField = 'set' . $field;
                $data = call_user_func(
                    array(
                        $this->encryptor,
                        $method
                    ),
                    $object->$getField()
                );
                $object->$setField($data);
            }
        }
    }
    /**
     * Fetches a specific entity from the repository by the given Id
     * @param string mixed The Id of the entity to be returned
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
     * @param array|string $order
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
            if (is_array($order)) {
                foreach($order as $orderOption) {
                    $qb = $qb->addOrderBy($orderOption[0], $orderOption[1]);
                }
            }
            if (is_string($order)) {
                $qb = $qb->orderBy($order);
            }
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
        $paginator = new Paginator($qb, $fetchJoinCollection = true);
        return array(
            'limit' => $limit,
            'offset' => $offset,
            'total' => count($paginator),
            'results' => $paginator
        );
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
