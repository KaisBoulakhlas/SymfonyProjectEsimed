<?php


namespace App\Notification;


use App\Entity\AdminUser;
use App\Entity\Advert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;

class AdvertCreatedNotification extends Notification implements EmailNotificationInterface
{

    private Advert $advert;
    private EntityManagerInterface $manager;

    public function __construct(Advert $advert, EntityManagerInterface $manager)
    {
        $this->advert = $advert;
        $this->manager = $manager;

        parent::__construct('Nouvelle annonce créée');
    }

    public function asEmailMessage(Recipient $recipient, string $transport = null): ?EmailMessage
    {
        $message = EmailMessage::fromNotification($this, $recipient);
        if (null !== $transport) {
            $message->transport($transport);
        }

        $users = $this->manager->getRepository(AdminUser::class)->findAll();

        foreach($users as $user){
            $message->getMessage()->addTo($user->getEmail());
        }

        $message
            ->getMessage()
            ->htmlTemplate('emails/advert_created_notification.html.twig')
            ->context(['advert' => $this->advert]);

        return $message;
    }

    public function getChannels(Recipient $recipient): array
    {
        return ['email'];
    }
}