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
	 * Checks if the provided token hash for a give userID is valid
	 * @param string $userId The UUID for the user
	 * @param string $hash The hash of the auth token
	 * @return bool The result of valdiating the user's hashed token
	 */
	public function validateTokenForUser($userId, $hash)
	{
		$userTokens = $this->qb()->where(
			$this->qb->expr()->eq('userId', $userId)
		)->getQuery()->getResult();
		if (!empty($users)) {
			foreach ($userTokens as $token) {
				if ($token->isHashValid($hash)) {
					return true;
				}
			}
		}
		return false;
	}
	/**
	 *
	 */
	public function create($values = array())
	{
		return $this->hyrate(new PersistentLogin(), $values);
	}
	/**
	 *
	 */
	public function save(PersistentLogin $object)
	{
		$this->em->persist($object);
		return $this;
	}
	/**
	 *
	 */
	public function remove(PersistentLogin $object)
	{
		$this->em->remove($object);
		$this->em->flush();
		return $this;
	}
}
