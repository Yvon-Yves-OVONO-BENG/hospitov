<?php

namespace App\Form;

use App\Entity\Consultation;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ConsultationType extends AbstractType
{
    public function __construct(
        private UserRepository $userRepository,
        private TranslatorInterface $translator,
        )
    {}
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            // ->add('examens', ChoiceType::class, [
            //     'choices' => $options['examens'],
            //     'multiple' => true,
            //     'required' => false,
            //     'expanded' => false,
            //     'mapped' => false,
            //     'attr' => [
            //         'class' => 'form-control select2',
            //     ]
            // ])
            // ->add('fichierResultatsExamenFile', VichImageType::class, [
            //     'label' => false,
            //     'required' => true,
            //     'allow_delete' => true,
            //     'delete_label' => "Supprimer",
            //     'download_uri' => false,
            //     'download_label' => "Télécharger",
            //     'image_uri' => true,
            //     'attr' => [
            //         'class' => 'dropify'
            //     ]
            // ])
            ->add('diagnostic', TextareaType::class, [
                'label' => $this->translator->trans("Diagnistics"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Veuillez saisir le diagnostic du patient ici"),
                ]
            ])
            ->add('symptomes', TextareaType::class, [
                'label' => $this->translator->trans("Symptomes"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Veuillez saisir les symptomes du patient ici"),
                ]
            ])
            ->add('medicaments', TextareaType::class, [
                'label' => $this->translator->trans("Symptomes"),
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Veuillez saisir les médicaments"),
                ]
            ])
            ->add('examens', TextareaType::class, [
                'label' => $this->translator->trans("Symptomes"),
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Veuillez saisir les examens si prévus"),
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
