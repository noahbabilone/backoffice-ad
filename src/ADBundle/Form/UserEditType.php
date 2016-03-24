<?php

namespace ADBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('name', 'text', array(
//                    'label' => 'Nom',
//                    'required' => true,
//                    'attr' => array(
//                        'class' => 'form-control name',
//                        'placeholder' => 'Nom',
//                    ),
//                )
//            )->add('firstName', 'text', array(
//                    'label' => 'Prenom',
//                    'required' => true,
//                    'attr' => array(
//                        'class' => 'form-control firstName',
//                        'placeholder' => 'Prenom',
//                    ),
//                )
//            )
            ->add('login', 'text', array(
                    'label' => 'Login',
                    'disabled' => true,
                    'attr' => array(
                        'class' => 'form-control login',
                        'placeholder' => 'Login',
                        
                    ),
                )
            )
            ->add('password', 'password', array(
                    'label' => 'Nouveau Mot de passe',
                    'attr' => array(
                        'class' => 'form-control password',
                        'placeholder' => 'Nouveau mot de passe',
                    ),
                )
            )
//            ->add('address', 'text', array(
//                    'label' => 'Adresse',
//                    'required' => false,
//                    'attr' => array(
//                        'class' => 'form-control',
//                        'placeholder' => 'Adresse',
//                    ),
//                )
//            )->add('street', 'number', array(
//                    'label' => 'Rue',
//                    'required' => false,
//                    'attr' => array(
//                        'class' => 'form-control',
//                        'placeholder' => 'Rue',
//                    ),
//                )
//            )
//            ->add('state', 'number', array(
//                    'label' => 'Code postal',
//                    'required' => false,
//                    'attr' => array(
//                        'class' => 'form-control',
//                        'placeholder' => '75001',
//                    ),
//                )
//            )->add('office', 'text', array(
//                    'label' => 'Poste',
//                    'required' => false,
//                    'attr' => array(
//                        'class' => 'form-control',
//                        'placeholder' => 'Ingénieur, Développeur,...',
//                    ),
//
//                )
//            )
//            ->add('city', 'text', array(
//                    'label' => 'Ville',
//                    'attr' => array(
//                        'class' => 'form-control',
//                        'placeholder' => 'Paris',
//                    ),
//
//                )
//            )  ->add('service', 'choice', array(
//                    'label' => 'Service',
//                    'choices' => array(
//                        "Saint-Mande"=>"42Consulting Paris",
//                        "Luxembourg"=>"42Consulting Lux",
//                        "Issy-Les-Moulineaux"=>"42Mtvc",
//                    ),
//                    'attr' => array(
//                        'class' => 'form-control',
//                        'placeholder' => 'service',
//                    ),
//
//                )
//            )
//            ->add('phone', 'number', array(
//                    'label' => 'Téléphone',
//                    'required' => false,
//                    'attr' => array(
//                        'class' => 'form-control',
//                        'placeholder' => 'Téléphone',
//                    ),
//
//                )
//            )
//            ->add('email', 'email', array(
//                    'label' => 'Email',
//                    'required' => true,
//                    'disabled'=> true,
//                    'attr' => array(
//                        'class' => 'form-control',
//                        'placeholder' => 'nom.prenom',),
//
//                )
//            )
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
