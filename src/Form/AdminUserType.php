<?php

namespace App\Form;

use App\Entity\AdminUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array(
                "required" => true,
                "label" => "Email :",
                "attr" => ['placeholder' => 'example@fake.com'],
            ))
            ->add('plainPassword',PasswordType::class, array(
                "required" => $options['mandatory'] === true,
                "label" => "Mot de passe :"
            ))
            ->add('username', TextType::class, array(
                "required" => true,
                "label" => "Pseudonyme :"
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AdminUser::class,
            'mandatory' => true
        ]);
    }
}
