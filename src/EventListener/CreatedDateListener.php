<?php


namespace App\EventListener;


use App\Entity\Advert;
use App\Entity\Picture;
use Doctrine\ORM\Event\LifecycleEventArgs;

class CreatedDateListener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args) : void
    {
        /**
         * @var Advert $entity
         */
        $entity = $args->getEntity();
        if(true === property_exists($entity, 'createdAt') && ($entity instanceof Advert || $entity instanceof Picture)){
            $date = new \DateTime();
            $date->setTimezone(new \DateTimeZone('Europe/Paris'));
            $entity->setCreatedAt($date);
        }
    }
}