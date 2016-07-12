<?php

namespace Inachis\Component\CoreBundle\Entity;

/**
 * Used to manage interactions with the {@link Page}
 * Entity/Repository
 */
class PageManager extends AbstractManager
{
    /**
     *
     * @return string
     */
    protected function getClass()
    {
        return 'Inachis\\Component\\CoreBundle\\Entity\\Page';
    }

    public function create($values = array())
    {
        return $this->hydrate(new Page(), $values);
    }
    
    public function save(Page $page)
    {
        $page->setModDate(new \DateTime('now'));
        $this->em->merge($page);
        $this->em->flush();
    }
    
    public function remove(Page $page)
    {
        $this->em->remove($page);
        $this->em->flush();
    }

    public function getDraftCount()
    {
        $qb = $this->getRepository()->createQueryBuilder('q');
        return (int) $qb
            ->select('count(q.id)')
            ->where('q.status = :status')
            ->setParameter('status', Page::DRAFT)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getPublishedCount()
    {
        $qb = $this->getRepository()->createQueryBuilder('q');
        return (int) $qb
            ->select('count(q.id)')
            ->where('q.status = :status')
            ->setParameter('status', Page::PUBLISHED)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
