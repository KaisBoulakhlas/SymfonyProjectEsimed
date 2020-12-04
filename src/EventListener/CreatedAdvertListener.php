<?php


namespace App\EventListener;


use App\Entity\Advert;
use App\Entity\Picture;
use App\Notification\AdvertCreatedNotification;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Notifier\NotifierInterface;

class CreatedAdvertListener
{
    private NotifierInterface $notifier;
    private EntityManagerInterface $manager;

    public function __construct(NotifierInterface $notifier, EntityManagerInterface $manager)
    {
        $this->notifier = $notifier;
        $this->manager = $manager;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args) : void
    {
        /**
         * @var Advert $advert
         */
        $advert = $args->getEntity();
        /**
         * @var Picture $picture
         */
        $picture = $args->getEntity();

        if(true === property_exists($advert, 'createdAt') && ($advert instanceof Advert || $picture instanceof Picture)){
            $date = new \DateTime();
            if(true === property_exists($advert, 'state')){
                $advert->setState("draft");
                $advert->setPublishedAt(null);
            }

            $date->setTimezone(new \DateTimeZone('Europe/Paris'));
            $advert->setCreatedAt($date);

        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args) : void
    {
        /**
         * @var Advert $advert
         */
        if($args->getEntity() instanceof Advert){
            $advert = $args->getEntity();
            $this->notifier->send(new AdvertCreatedNotification($advert,$this->manager), ...$this->notifier->getAdminRecipients());
        }
    }
}