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
            ->add('title', 'text', array(
                    'label' => 'Fonction',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Fonction',
                        'autocomplete' => 'off'
                    ),
                )
            )->add('name', 'text', array(
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
            ->add('description', 'textarea', array(
                    'label' => 'Description',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Ingénieur, Développeur,...',
                        'autocomplete' => 'off'
                    ),

                )
            )
            ->add('address', 'text', array(
                    'label' => 'Adresse',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control address',
                        'placeholder' => 'Adresse',
                        'autocomplete' => 'off'
                    ),
                )
            )
            ->add('postalCode', 'number', array(
                    'label' => 'Code postal',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control postalCode',
                        'placeholder' => '75001',
                        'autocomplete' => 'off'
                    ),
                )
            )
            ->add('city', 'text', array(
                    'label' => 'Ville',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control city',
                        'placeholder' => 'Paris',
                    ),

                )
            )->add('country', 'text', array(
                    'label' => 'Pays',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control country',
                        'placeholder' => 'France',
                    ),

                )
            )->add('service', 'choice', array(
                    'label' => 'Service',
                    'choices' => array(
                        "Saint-Mandé" => "42Consulting Paris",
                        "Luxembourg" => "42Consulting Lux",
                        "Issy-Les-Moulineaux" => "42MediaTelecom",
//                        "test1" => "test 1",
//                        "test2" => "test 2",
//                        "test3" => "test 3",

                    ),
                    'attr' => array(
                        'class' => 'form-control service',
                        'placeholder' => 'service',
                    ),

                )
            )->add('group', 'hidden', array(
                    'label' => '',
                    'attr' => array(
                        'class' => 'select-groups',
                        'autocomplete' => 'off'

                    ),
                )
            )
            ->add('at', 'choice', array(
                    'label' => ' ',
                    'choices' => array(
                        "42consulting.fr" => "42consulting.fr",
                        "42consulting.lu" => "42consulting.lu",
                        "42mediatvcom.fr" => "42mediatvcom.fr",
                        "42consulting.ma" => "42consulting.ma",
                        "42consulting.nl" => "42consulting.nl",
                    ),
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'service',
                    ),

                )
            )
            ->add('licence', 'choice', array(
                    'label' => 'Licence',
                    'choices' => array(
                        "K1" => "K1 Webmail",
                        "E3" => "E3 Pack Office 2016",
                    ),
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'service',
                    ),

                )
            )
            ->add('phone', 'text', array(
                    'label' => 'Fix',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control phone',
                        'placeholder' => '0105020304',
                        'autocomplete' => 'off'
                    ),

                )
            )->add('mobile', 'text', array(
                    'label' => 'Mobile',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control mobile',
                        'placeholder' => '0601020304',
                        'autocomplete' => 'off'
                    ),

                )
            )
            ->add('email', 'text', array(
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
