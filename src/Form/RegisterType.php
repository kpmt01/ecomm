<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',TextType::class, array('label' => 'Adı', 'attr' => array('class' => 'form-control mb-4') ))
            ->add('password', PasswordType::class, array('label' => 'Şifre', 'attr' => array('class' => 'form-control mb-4') ))
            ->add('email', EmailType::class, array('label' => 'E-Posta', 'attr' => array('class' => 'form-control mb-4') ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
