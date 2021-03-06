<?php

namespace App\Form\Front;

use App\Entity\User;
use App\Form\Recaptcha3SubmitType;
use App\Repository\ConfigRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgentType extends AbstractType
{
    private $recaptchaActivated;

    public function __construct(ConfigRepository $configRepository)
    {
        $this->recaptchaActivated = $configRepository->findOneByName("recaptcha_activated")->get();
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['create']) {
            $builder
            ->add('email', EmailType::class, [
                'label' => 'agent.label.email',
            ]);
        }

        $builder
            ->add('firstname', TextType::class, [
                'label' => 'agent.label.firstname',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'agent.label.lastname',
            ])
            ->add('shortDescription', TextareaType::class, [
                'label' => 'agent.label.shortDescription',
                'help' => 'Affiché dans la page de recherche des agents',
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'agent.label.description',
                'help' => 'Affiché dans la page de présentation',
            ])
            ->add('phone1', TextType::class, [
                'label' => 'agent.label.phone1',
            ])
            ->add('address', UserAddressType::class, [
                'label' => false,
                'required' => true,
            ])
            ->add('link', UrlType::class, [
                'label' => 'Url de Google My Business',
                'required' => false,
            ]);
    
        if ($options['create'] && $this->recaptchaActivated) {
            $builder
            ->add('submit', Recaptcha3SubmitType::class, [
                'parent_builder' => $builder,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'front_messages',
            'create' => true,
        ]);
    }
}