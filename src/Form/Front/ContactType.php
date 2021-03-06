<?php

namespace App\Form\Front;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('phone')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Demande d\'information générale' => 'Demande d\'information générale',
                    'Demande d\'information tarifaire' => 'Demande d\'information tarifaire',
                    'Proposition commerciale' => 'Proposition commerciale',
                    'Autre' => 'Autre',
                ],
            ])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'rows' =>'5',
                    'cols' => '50'
                ],
             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
