<?php

namespace App\Form\Front;

use App\Manager\Front\AgentManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgentFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'label.filter_search',
                ],
                'required' => false,
            ])
            ->add('number_by_page', IntegerType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'label.filter_number_by_page',
                ],
                'empty_data' => AgentManager::NUMBER_BY_PAGE,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'method' => 'GET',
            'translation_domain' => 'front_messages',
        ]);
    }
    
    public function getBlockPrefix()
    {
        return 'filter';
    }
}
