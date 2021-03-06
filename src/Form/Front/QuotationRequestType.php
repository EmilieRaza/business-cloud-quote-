<?php

namespace App\Form\Front;

use App\Entity\QuotationRequest;
use App\Form\Recaptcha3SubmitType;
use App\Repository\ConfigRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuotationRequestType extends AbstractType
{
    private $recaptchaActivated;

    public function __construct(ConfigRepository $configRepository)
    {
        $this->recaptchaActivated = $configRepository->findOneByName("recaptcha_activated")->get();
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('deceasedFirstname', TextType::class, [
                'label' => '* Prénom',
            ])
            ->add('deceasedLastname', TextType::class, [
                'label' => '* Nom',
            ])
            ->add('deceasedAddress', TextType::class, [
                'label' => '* Adresse',
            ])
            ->add('deathPlace', TextType::class, [
                'label' => '* Lieu du décès',
                'help' => "Nom de l'établissement ou adresse",
            ])
            ->add('funeralType', ChoiceType::class, [
                'label' => '* Type',
                'choices' => [
                    'Crémation' => 'Crémation',
                    'Inhumation' => 'Inhumation',
                ],
                'placeholder' => '---',
            ])
            ->add('ashesDestination', TextType::class, [
                'label' => '* Destination des cendres',
                'help' => 'Dispersion au jardin du souvenir / Cimetière',
                'required' => false,
            ])
            ->add('burialDestination', TextType::class, [
                'label' => '* Lieu cimetière souhaité',
                'required' => false,
            ])
            ->add('contemplation', ChoiceType::class, [
                'label' => '* Cérémonie',
                'choices' => [
                    'Civile' => 'Civil',
                    'Religieuse' => 'Religieux',
                ],
                'placeholder' => '---',
            ])
            ->add('contactFirstname', TextType::class, [
                'label' => '* Prénom',
            ])
            ->add('contactLastname', TextType::class, [
                'label' => '* Nom',
            ])
            ->add('contactPhone', TextType::class, [
                'label' => '* Téléphone',
            ])
            ->add('contactEmail', EmailType::class, [
                'label' => '* E-mail',
            ])
            ->add('link_with_deceased', TextType::class, [
                'label' => 'Lien avec le défunt',
            ]);
        if ($this->recaptchaActivated) {
            $builder
                ->add('submit', Recaptcha3SubmitType::class, [
                    'parent_builder' => $builder,
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => QuotationRequest::class,
            'translation_domain' => 'back_messages',
        ]);
    }
}
