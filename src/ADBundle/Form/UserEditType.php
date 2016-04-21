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
            ->add('name', 'text', array(
                    'label' => 'Nom',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control name',
                        'placeholder' => 'Nom',
                    ),
                )
            )->add('firstName', 'text', array(
                    'label' => 'Prenom',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control firstName',
                        'placeholder' => 'Prenom',
                    ),
                )
            )
//            ->add('fullName', 'text', array(
//                    'label' => 'Nom Complet',
//                    'disabled' => true,
//                    'required' => true,
//                    'attr' => array(
//                        'class' => 'form-control fullName',
//                        'placeholder' => 'Nom Complet',
//                    ),
//                )
//            )
            ->add('login', 'text', array(
                    'label' => 'Login',
                    'disabled' => true,
                    'attr' => array(
                        'class' => 'form-control login',
                        'placeholder' => 'Login',
                        'autocomplete' => 'off'

                    ),
                )
            )
            ->add('password', 'password', array(
                    'label' => 'Nouveau Mot de passe',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control password',
                        'placeholder' => 'Nouveau mot de passe',
                    ),
                )
            )
            ->add('address', 'text', array(
                    'label' => 'Adresse',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Adresse',
                    ),
                )
            )
            ->add('postalCode', 'number', array(
                    'label' => 'Code postal',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => '75001',
                    ),
                )
            )->add('description', 'textarea', array(
                    'label' => 'Description',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Ingénieur, Développeur,...',
                        'autocomplete' => 'off'
                    ),

                )
            )
            ->add('city', 'text', array(
                    'label' => 'Ville',
                    'required' => false,

                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Paris',
                    ),

                )
            )->add('service', 'choice', array(
                    'label' => 'Service',
                    'choices_as_values' => true,
                    'choices' => array(
                        "42Consulting Paris" => "Saint-Mandé",
                        "42Consulting Lux" => "Luxembourg",
                        "42MediaTelecom" => "Issy-Les-Moulineaux",
                        "test" => "test",
                        "test2" => "test2",
                        "test3" => "test3",
                    ),
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'service',
                    ),

                )
            )
            ->add('phone', 'number', array(
                    'label' => 'Téléphone',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Téléphone',
                    ),

                )
            )
            ->add('email', 'email', array(
                    'label' => 'Email',
                    'required' => true,
                    'disabled' => true,
                    'attr' => array(
                        'class' => 'form-control email',
                        'placeholder' => 'nom.prenom',),

                )
            )
            ->add('at', 'choice', array(
                    'label' => ' ',
                    'choices' => array(
                        "42consulting.fr" => "42consulting.fr",
                        "42consulting.lu" => "42consulting.lu",
                        "42Madiatvcom.fr" => "42Mediatvcom.fr",
                    ),
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'service',
                    ),

                )
            );

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
