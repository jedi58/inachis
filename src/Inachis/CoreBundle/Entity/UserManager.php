<?php

namespace Inachis\Component\CoreBundle\Entity;

use Inachis\Component\CoreBundle\Application;
use Inachis\Component\CoreBundle\Entity\AbstractManager;
use Doctrine\ORM\QueryBuilder;

class UserManager extends AbstractManager
{
    /**
     * Returns the namespace of the current Entity
     * @return string The namespaced entity
     */
    protected function getClass()
    {
        return 'Inachis\\Component\\CoreBundle\\Entity\\User';
    }
    /**
     * Returns an array of fields which will be encrypted in the repository
     * @return string[] The array of encrypted fields
     */
    protected function getEncryptedFields()
    {
        return array('password', 'displayName');
    }
    /**
     * Default constructor for {@link UserManager} - utilises parent constructor
     * and then sets an encryptor object for use by encrypted fields
     * @param EntityManager Used for repository interactions
     */
    public function __construct($em)
    {
        parent::__construct($em);
        $this->encryptor = Application::getInstance()->requireEncryptionService();
    }
    /**
     * Creates a new {@link User} objectm hydrated with values from given array
     * @param mixed[] $values The array of values to hydrate the object with
     * @return User The hydrated user object
     */
    public function create($values = array())
    {
        return $this->hydrate(new User(), $values);
    }
    /**
     * Saves the current state of the {@link User} object to the repository
     * @param User $user The user to insert/update
     */
    public function save(User $user)
    {
        $this->encryptFields($user);
        $user->setModDate(new \DateTime('now'));
        $this->em->persist($user);
        $this->em->flush();
    }
    /**
     * Removes the {@link User} object from the repository
     * @param User $user The user to remove
     */
    public function remove(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();
    }
    /**
     * Fetches a specific entity from the repository by the given Id
     * @param string The Id of the entity to be returned
     * @return mixed The returned entity
     */
    public function getById($id)
    {
        $user = $this->getRepository()->find($id);
        $this->decryptFields($user);
        return $user;
    }
    /**
     * Returns a {@link User} based on the specified username
     * @param string $username The username of the {@link User} to return
     * @return User The retrieved user object
     */
    public function getByUsername($username)
    {
        $user = $this->getRepository()->findOneBy(array('username' => $username));
        $this->decryptFields($user);
        return $user;
    }
    /**
     *
     */
    public function qbByUsernameOrEmail($usernameOrEmail)
    {
        $qb = $this->getRepository()->createQueryBuilder('u')
            ->where($qb->expr()->like('username', ':username'))
            ->whereOr($qb->expr()->like('email', ':username'))
            ->setParameter('username', $usernameOrEmail);
        return $qb;
    }
    /**
     * Returns a {@link User} based on the specified username or email address
     * @param string $username The username/email address of the {@link User} to return
     * @return User The retrieved user object
     */
    public function getByUsernameOrEmail($usernameOrEmail)
    {
        return $this->qbByUsernameAndEmail($usernameOrEmail)
            ->getQuery()
            ->getResult();
    }

    public function getUserCount()
    {
        return (int) $this->getRepository()->createQueryBuilder('u')
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
