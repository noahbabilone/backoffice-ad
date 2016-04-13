<?php

namespace ADBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditPasswordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('login', 'text', array(
                    'label' => 'Login',
                    'disabled' => true,
                    'attr' => array(
                        'class' => 'form-control login',
                        'placeholder' => 'Email',
                        'autocomplete' => 'off'
                    ),
                )
            )
            ->add('password', 'password', array(
                    'label' => 'Nouveau mot de passe',
                    'attr' => array(
                        'class' => 'form-control password',
                        'placeholder' => 'Nouveau mot de passe',
                    ),
                )
            )->add('oldPassword', 'password', array(
                    'label' => 'Mot de passe actuel',
                    'attr' => array(
                        'class' => 'form-control old-password',
                        'placeholder' => 'Mot de passe actuel',
                    ),
                )
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ADBundle\Entity\User'
        ));
    }
}
