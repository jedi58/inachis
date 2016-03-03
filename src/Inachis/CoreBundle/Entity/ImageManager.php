<?php

namespace Inachis\Component\CoreBundle\Entity;

use Inachis\Component\CoreBundle\Entity\AbstractManager;

class ImageManager extends AbstractManager
{
    /**
     *
     * @return string
     */
    protected function getClass()
    {
        return 'Image';
    }
    
    public function create($values = array())
    {
        return $this->hydrate(new Image(), $values);
    }
    
    public function save(Image $image)
    {
        $image->setModDateFromDateTime(new \DateTime('now'));
        $this->em->persist($image);
        $this->em->flush();
    }
    
    public function remove(Image $image)
    {
        $this->em->remove($image);
        $this->em->flush();
    }

    public function getByFilename($filename)
    {
        return $this->getRepository()->findOneBy(array('filename' => $filename));
    }
}
