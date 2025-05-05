<?php

namespace App\Form;

use App\Entity\FichierResultatExamen;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ResultatExamenType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator)
    {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'label' => false,
                'required' => true,
                'allow_delete' => true,
                'delete_label' => $this->translator->trans("Supprimer"),
                'download_uri' => false,
                'download_label' => $this->translator->trans("Télécharger"),
                'image_uri' => true,
                'attr' => [
                    'class' => 'dropify'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FichierResultatExamen::class,
        ]);
    }
}
