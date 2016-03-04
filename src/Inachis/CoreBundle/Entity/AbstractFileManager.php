<?php

namespace Inachis\Component\CoreBundle\Entity;

abstract class AbstractFileManager extends AbstractManager
{
    /**
     * Returns a single entity specified by it's filename
     * @param string $filename The filename to get the entity record for
     * @return mixed The retrieved entity
     */
    public function getByFilename($filename)
    {
        return $this->getRepository()->findOneBy(array('filename' => $filename));
    }
    /**
     *
     */
    private function qbGetByFiletypes($types = array())
    {
    	$qb = $this->getRepository()->createQueryBuilder('f');
    	return $qb->where(
            $qb->expr()->in('f.filetype', $types)
        );
    }
    /**
     *
     */
    public function getByFiletypes($types = array())
    {
        return $this->qbGetByFiletypes($types)->getQuery()->getResult();
    }
    /**
     *
     */
    public function getByFiletypesCount($types = array())
    {
        return (int) $this->qbGetByFiletypes($types)
            ->select('count(f.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
