<?php

namespace App\Form;

use App\Entity\Ambulance;
use App\Entity\EtatAmbulance;
use Symfony\Component\Form\AbstractType;
use App\Repository\EtatAmbulanceRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AmbulanceType extends AbstractType
{
    public function __construct(
        protected TranslatorInterface $translator,
        )
    {}
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ambulance', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Ambulance"),
                ],
                
            ])
            ->add('immatriculation', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Immatriculation"),
                ],
            ])
            
            ->add('etatAmbulance', EntityType::class, [
                'class' => EtatAmbulance::class,
                'choice_label' => 'etatAmbulance',
                'required' => true,
                'placeholder' => $this->translator->trans('- - -'),
                'attr' => [
                    'class' => 'form-control select2-show-search',
                ],
                'query_builder' => function(EtatAmbulanceRepository $etatAmbulanceRepository){
                    
                    return $etatAmbulanceRepository->createQueryBuilder('e')->orderBy('e.etatAmbulance');
                },
            ])
            ->add('anneeAt', DateType::class, [
                'label' => $this->translator->trans('Année'),
                'widget' => 'single_text'
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
                    'class' => 'form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ambulance::class,
        ]);
    }
}
