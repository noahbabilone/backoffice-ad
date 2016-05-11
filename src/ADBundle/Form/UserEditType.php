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
            ->add('password', 'password', array(
                    'label' => 'Mot de passe',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control password',
                        'placeholder' => 'Mot de passe',
                        'autocomplete' => 'off'
                    ),
                )
            )
            ->add('groupNotSelect', 'hidden', array(
                    'label' => '',
                    'required' => false,
                    'attr' => array(
                        'class' => 'select-groups',
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

    public function getParent()
    {
        return new UserType();
    }
}
