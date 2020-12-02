<?php


namespace App\EventListener;


use App\Entity\AdminUser;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){

        $this->encoder = $encoder;
    }

    /**
     * @param LifecycleEventArgs $args
     * @ORM\PrePersist()
     */
    public function prePersist(LifecycleEventArgs $args) : void
    {
        /**
         * @var AdminUser $entity
         */
        $entity = $args->getEntity();
        if(true === property_exists($entity, 'password') && $entity instanceof AdminUser){
            $encoded = $this->encoder->encodePassword($entity, $entity->getPlainPassword());
            $entity->setPassword($encoded);
            $entity->setRoles(['ROLE_ADMIN']);
        }
    }


    /**
     * @param LifecycleEventArgs $args
     * @ORM\PreUpdate()
     */
    public function preUpdate(LifecycleEventArgs $args) : void
    {
        /**
         * @var AdminUser $entity
         */
        $entity = $args->getEntity();
        if (true === property_exists($entity, 'password') && $entity instanceof AdminUser) {
            if ($entity->getPlainPassword() !== null) {
                $encoded = $this->encoder->encodePassword($entity, $entity->getPlainPassword());
                $entity->setPassword($encoded);
            }
        }
    }
}