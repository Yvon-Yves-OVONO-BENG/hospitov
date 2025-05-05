<?php

namespace App\Form;

use App\Entity\Consultation;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class EnvoieFicherExamenType extends AbstractType
{
    public function __construct(
        protected UserRepository $userRepository,
        protected TranslatorInterface $translator,
        )
    {}
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
            ->add('fichierResultatsExamenFile', VichImageType::class, [
                'label' => false,
                'required' => true,
                'allow_delete' => true,
                'delete_label' => "Supprimer",
                'download_uri' => false,
                'download_label' => "Télécharger",
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
            'data_class' => Consultation::class,
            'examens' => [],
        ]);
    }
}
