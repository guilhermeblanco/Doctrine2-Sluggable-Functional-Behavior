<?php

namespace DoctrineExtensions\Sluggable;

use Doctrine\Common\EventSubscriber,
    Doctrine\ORM\Event\LifecycleEventArgs;

class SluggableSubscriber implements EventSubscriber
{
    /**
     * Constant which points to prePersist event
     */
    const prePersist = 'prePersist';

    /**
     * Return the events to subscribe for monitoring
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(self::prePersist);
    }

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
