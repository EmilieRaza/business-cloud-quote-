<?php

namespace App\Form\Back;

use App\Entity\User;
use App\Manager\Back\QuotationRequestManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuotationRequestFilterType extends AbstractType
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
            ->add('agent', EntityType::class, [
                'label' => false,
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles LIKE :role')
                        ->andWhere('u.enabled = 1')
                        ->setParameter('role', '%"ROLE_AGENT"%')
                        ->orderBy('u.lastname', 'ASC');
                },
                'expanded' => false,
                'multiple' => false,
                'placeholder' => 'Agent',
                'required' => false,
            ])
            ->add('number_by_page', IntegerType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'label.filter_number_by_page',
                ],
                'empty_data' => QuotationRequestManager::NUMBER_BY_PAGE,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'method' => 'GET',
            'translation_domain' => 'back_messages',
        ]);
    }
    
    public function getBlockPrefix()
    {
        return 'filter';
    }
}
