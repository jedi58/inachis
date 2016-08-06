<?php

namespace Inachis\Component\CoreBundle\Entity\Security;

use Inachis\Component\CoreBundle\Entity\AbstractManager;

/**
 * @todo need to think about GC and wiping existing tokens on attempted hijack
 */
class PersistentLoginManager extends AbstractManager
{
    /**
     * @var Doctrine ORM query builder for use in querying repository
     */
	protected $qb;
	/**
	 * Default constructor for {@link PersistentLoginManager}
	 * @param EntityManager $em The entity manager to use for the repository
	 */
	public function __construct($em)
	{
        parent::__construct($em);
        $this->qb = $this->getRepository()->createQueryBuilder('u');
	}
    /**
     * Returns the namespace of the current Entity
     * @return string The namespaced entity
     */
    protected function getClass()
    {
        return 'Inachis\\Component\\CoreBundle\\Entity\\Security\\PersistentLogin';
    }
	/**
     * Creates and returns a new instance of {@link PersistentLogin}
     * @return Url The new entity
	 */
	public function create($values = array())
	{
		return $this->hydrate(new PersistentLogin(), $values);
	}
	/**
     * Saves the current entity (assuming it is attached)
     * @param PersistentLogin $object The {@link PersistentLogin} object to save
	 * @return PersistentLoginManager Returns itself
	 */
	public function save(PersistentLogin $object)
	{
		$this->em->persist($object);
		return $this;
	}
	/**
     * Removes the current entity (assuming it is attached) from the repository
     * @param PersistentLogin $object The {@link PersistentLogin} object to remove
	 */
	public function remove(PersistentLogin $object)
	{
		$this->em->remove($object);
		$this->em->flush();
	}
	/**
	 * Checks if the provided token hash for a give userID is valid
	 * @param string $userHash The UUID for the user
	 * @param string $hash The hash of the auth token
	 * @return bool|PersistentLogin The result of valdiating the user's hashed token
	 */
	public function validateTokenForUser($userHash, $hash)
	{
		$userTokens = $this->qb
			->where($this->qb->expr()->eq('u.userHash', ':userHash'))
			->setParameter('userHash', $userHash)
			->getQuery()
			->getResult();
		if (!empty($userTokens)) {
			foreach ($userTokens as $token) {
				if ($token->isTokenHashValid($hash)) {
					return $token;
				}
			}
		}
		return false;
	}
}
