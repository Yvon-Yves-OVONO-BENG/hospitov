<?php

namespace App\Form;

use App\Entity\Batiment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BatimentType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator,
        )
    {}
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('batiment', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Bâtiment"),
                ]
            ])
            ->add('photoFile', VichImageType::class, [
                'label' => false,
                'required' => false,
                'allow_delete' => true,
                'delete_label' => "Supprimer",
                'download_uri' => false,
                'download_label' => "Télécharger",
                'image_uri' => true,
                'attr' => [
                    'class' => 'dropify'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '1M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image JPEG ou PNG valide dont la capasité est inférieure à 1Mo.',
                    ])
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Batiment::class,
        ]);
    }
}
