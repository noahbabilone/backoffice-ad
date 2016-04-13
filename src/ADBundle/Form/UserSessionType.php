<?php

namespace ADBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSessionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', 'text', array(
                'label' => 'Email',
                'attr' => array(
                    'class' => 'form-control validate email',
                   // 'placeholder' => 'Email',
                    ),
            ))
            ->add('password', 'password', array(
                'label' => 'Mot de passe',
                'attr' => array(
                    'class' => 'form-control validate password',
                    'data-placement'=>'right', 
                   // 'placeholder' => 'Mot de passe',
                    ),

            ));
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
