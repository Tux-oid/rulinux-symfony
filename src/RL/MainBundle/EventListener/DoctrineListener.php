<?php

namespace RL\MainBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use RL\MainBundle\Entity\Mark;
use Symfony\Component\DependencyInjection\Container;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;


/**
 * RL\MainBundle\EventListener\DoctrineListener
 *
 * @Service("rl.main.doctrine_listener")
 * @Tag("doctrine.event_listener", attributes = {"event" = "postLoad"})
 *
 * @author Tux-oid
 */
class DoctrineListener
{

    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    private $container;

    /**
     * @param \Symfony\Component\DependencyInjection\Container $container
     *
     * @InjectParams({
     * "container" = @Inject("service_container")
     * })
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        if(($entity=$args->getEntity()) instanceof Mark){
            $entity->setEntityManager($this->container->get('doctrine.orm.entity_manager'));
        }
    }
}
