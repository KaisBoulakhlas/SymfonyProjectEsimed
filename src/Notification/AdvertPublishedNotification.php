<?php


namespace App\Notification;


use App\Entity\Advert;
use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;

class AdvertPublishedNotification extends Notification implements EmailNotificationInterface
{

    private Advert $advert;

    public function __construct(Advert $advert)
    {
        $this->advert = $advert;

        parent::__construct('Nouvelle annonce publiée');
    }

    public function asEmailMessage(Recipient $recipient, string $transport = null): ?EmailMessage
    {
        $message = EmailMessage::fromNotification($this, $recipient);
        if (null !== $transport) {
            $message->transport($transport);
        }

        $message
            ->getMessage()
            ->htmlTemplate('emails/advert_published_notification.html.twig')
            ->context(['advert' => $this->advert]);

        return $message;
    }

    public function getChannels(Recipient $recipient): array
    {
        return ['email'];
    }
}