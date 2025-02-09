<?php

namespace App\Form\Front;

use App\Entity\File;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class PhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', DropzoneType::class, [
                'label' => false,
                'attr' => [ 'placeholder' => 'Glisser-déposer ou parcourir', ],
                'constraints' => [
                    new Image(),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => File::class,
        ]);
    }
}
