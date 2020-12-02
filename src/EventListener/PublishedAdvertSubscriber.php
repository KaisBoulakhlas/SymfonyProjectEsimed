<?php


namespace App\EventListener;


use App\Entity\Advert;
use App\Notification\AdvertPublishedNotification;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Workflow\Event\Event;


class PublishedAdvertSubscriber implements EventSubscriberInterface
{
    private NotifierInterface $notifier;

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.advert.transition.to_published' => 'setPublishedAtAndNotifer'
        ];
    }

    /**
     * @param Event $event
     */
    public function setPublishedAtAndNotifer(Event $event)
    {
        /**
         * @var Advert $advert
         */
        $advert = $event->getSubject();
        if(!$advert->getPublishedAt()){
            $date = new \DateTime();
            $date->setTimezone(new \DateTimeZone('Europe/Paris'));
            $advert->setPublishedAt($date);
          //  $this->notifier->send(new AdvertPublishedNotification($advert), ...$this->notifier->getAdminRecipients());
        }
    }
}