<?php

namespace App\Form\Back;

use App\Entity\QuotationRequest;
use App\Manager\Back\QuotationRequestManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;

class QuotationRequestBatchType extends AbstractType
{
    
    /**
     * 
     * @var QuotationRequestManager     */
    private $quotationRequestManager;
    
    /**
     *
     * @param QuotationRequestManager $quotationRequestManager 
     */
    public function __construct(QuotationRequestManager $quotationRequestManager)
    {
        $this->quotationRequestManager = $quotationRequestManager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quotation_requests', EntityType::class, [
                'label' => false,
                'choice_label' => false,
                'class' => QuotationRequest::class,
                'choices' => $options['quotation_requests'],
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('action', ChoiceType::class, [
                'label' => false,
                'placeholder' => 'Action',
                'choices' => [
                    'action.delete' => 'delete',
                ],
                'multiple' => false,
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {            
                $result = $this->quotationRequestManager->validationBatchForm($event->getForm());
                if (true !== $result) {
                    $event->getForm()->addError(new FormError($result));
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'quotation_requests' => null,
            'translation_domain' => 'back_messages',
        ]);
    }
}
