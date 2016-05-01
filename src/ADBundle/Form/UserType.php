<?php

namespace ADBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
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
                        'autocomplete' => 'off'
                    ),
                )
            )->add('firstName', 'text', array(
                    'label' => 'Prenom',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control firstName',
                        'placeholder' => 'Prenom',
                        'autocomplete' => 'off'
                    ),
                )
            )
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
                    'label' => 'Mot de passe',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control password',
                        'placeholder' => 'Mot de passe',
                        'autocomplete' => 'off'
                    ),
                )
            )
            ->add('address', 'text', array(
                    'label' => 'Adresse',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Adresse',
                        'autocomplete' => 'off'
                    ),
                )
            )
            ->add('postalCode', 'number', array(
                    'label' => 'Code postal',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => '75001',
                        'autocomplete' => 'off'
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
            )->add('country', 'text', array(
                    'label' => 'Pays',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'France',
                    ),

                )
            )->add('service', 'choice', array(
                    'label' => 'Service',
                    'choices' => array(
                        "Saint-Mandé" => "42Consulting Paris",
                        "Luxembourg" => "42Consulting Lux",
                        "Issy-Les-Moulineaux" => "42MediaTelecom",
                        "test1" => "test 1",
                        "test2" => "test 2",
                        "test3" => "test 3",

                    ),
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'service',
                    ),

                )
            )->add('group', 'hidden', array(
                    'label' => '',
                    'attr' => array(
                        'class' => 'select-groups',
                    ),
                )
            )
            ->add('at', 'choice', array(
                    'label' => ' ',
                    'choices' => array(
                        "42consulting.fr" => "42consulting.fr",
                        "42consulting.lu" => "42consulting.lu",
                        "42mediatvcom.fr" => "42mediatvcom.fr",
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
                        'autocomplete' => 'off'
                    ),

                )
            ) ->add('office', 'number', array(
                    'label' => 'Poste',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Ingénieur, Administrateur,...',
                        'autocomplete' => 'off'
                    ),

                )
            )
            ->add('email', 'email', array(
                    'label' => 'Email',
                    'required' => true,
                    'disabled' => true,
                    'attr' => array(
                        'class' => 'form-control email',
                        'placeholder' => 'nom.prenom',
                        'autocomplete' => 'off'
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
