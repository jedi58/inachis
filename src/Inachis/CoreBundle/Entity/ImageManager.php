<?php

namespace Inachis\Component\CoreBundle\Entity;

use Inachis\Component\CoreBundle\Entity\AbstractManager;

class ImageManager extends AbstractFileManager
{
    /**
     * Returns the name of the Repository
     * @return string The name of the repository
     */
    protected function getClass()
    {
        return 'Inachis\\Component\\CoreBundle\\Entity\\Image';
    }
    /**
     * Returns an instance of {@link Image} hydrated with
     * values from the provided array
     * @param array[mixed] The array of values to apply to Image
     * @return Image The hydrated {@link Image}
     */
    public function create($values = array())
    {
        return $this->hydrate(new Image(), $values);
    }
    /**
     * Saves the provided {@link Image} to it's repository
     * @param Image $image The image to save
     */
    public function save(Image $image)
    {
        $image->setModDateFromDateTime(new \DateTime('now'));
        $this->em->persist($image);
        $this->em->flush();
    }
    /**
     * Removed the provided {@link Image} from it's repository
     * @param Image $image The image to remove
     */    
    public function remove(Image $image)
    {
        $this->em->remove($image);
        $this->em->flush();
    }
}
