<?php

namespace App\Form;

use App\Entity\Patient;
use App\Entity\Consultation;
use App\Repository\PatientRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ParametreType extends AbstractType
{
    public function __construct(
        private UserRepository $userRepository,
        private TranslatorInterface $translator,
        )
    {}
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // $utilisateurs = $this->userRepository->medecins();
        
        // $choixUtilisateurs = [];

        // foreach ($utilisateurs as $utilisateur) 
        // {
        //     $label = $utilisateur->getNom();

        //     $label .= " - ".$utilisateur->getTypeUtilisateur()->getTypeUtilisateur();

        //     if ($utilisateur->getSpecialite()) 
        //     {
        //         $label .= " - ".$utilisateur->getSpecialite()->getSpecialite();
        //     }

        //     $choixUtilisateurs[$label] = $utilisateur->getId();
        // }
        
        $builder
            ->add('temperature', TextType::class, [
                'label' => $this->translator->trans("Température"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Température"),
                ]
            ])
            ->add('tension', TextType::class, [
                'label' => $this->translator->trans("Tension"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Tension"),
                ]
            ])
            ->add('poids', TextType::class, [
                'label' => $this->translator->trans("Poids"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Poids"),
                ]
            ])
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => function (Patient $patient) {
                    return $patient->getNom()." - ".$patient->getCode();
                },
                'required' => true,
                'placeholder' => $this->translator->trans('- - -'),
                'attr' => [
                    'class' => 'form-control select2-show-search',
                ],
                'query_builder' => function(PatientRepository $patientRepository){
                    
                    return $patientRepository->createQueryBuilder('p')
                                            ->andWhere('p.termine = 0')
                                            ->orderBy('p.nom');
                },
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}
