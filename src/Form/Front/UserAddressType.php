<?php

namespace App\Form\Front;

use App\Entity\UserAddress;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('street', TextType::class, [
                'label' => 'user_address.label.street',
            ])
            ->add('complement', TextType::class, [
                'label' => 'user_address.label.complement',
                'required' => false,
            ])
            ->add('city', TextType::class, [
                'label' => 'user_address.label.city',
            ])
            ->add('zipCode', TextType::class, [
                'label' => 'user_address.label.zip_code',
            ])
            ->add('other', TextType::class, [
                'label' => 'user_address.label.other',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserAddress::class,
            'translation_domain' => 'front_messages',
        ]);
    }
}
