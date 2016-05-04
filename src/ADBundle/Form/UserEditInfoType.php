<?php

namespace ADBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditInfoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('login')
            ->remove('password')
            ->remove('name')
            ->remove('firstName')
            ->remove('at')
            ->remove('service')
            ->remove('address')
            ->remove('postalCode')
            ->remove('city')
            ->remove('country')
            ->remove('email')
            ->remove('description')
        ;
    }


    public function getName()
    {
        return 'ad_edit_info_user';
    }

    public function getParent()
    {
        return new UserType();
    }
}
