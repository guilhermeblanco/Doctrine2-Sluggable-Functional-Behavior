<?php

namespace DoctrineExtensions\Sluggable;

use Doctrine\ORM\Event\LifecycleEventArgs,
    Doctrine\ORM\EntityManager;

class SluggableListener
{
    /**
     * prePersist
     * 
     * @param LifecycleEventArgs $e Event
     */
    public function prePersist(LifecycleEventArgs $e)
    {
        $entity = $e->getEntity();

        if ($entity instanceof Sluggable) {
            $generator = new SlugGenerator($e->getEntityManager());
            $generator->process($entity);
        }
    }
}
