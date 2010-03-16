<?php

namespace DoctrineExtensions\Sluggable;

use Doctrine\ORM\Event\LifecycleEventArgs,
    Doctrine\Common\EventManager,
    Doctrine\ORM\Events;

class SluggableListener
{
    /**
     * Constructor
     *
     * @param EventManager $evm Event Manager
     */
    public function __construct(EventManager $evm)
    {
        $evm->addEventListener(Events::prePersist, $this);
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
