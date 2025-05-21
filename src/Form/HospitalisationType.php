<?php

namespace App\Form;

use App\Entity\Hospitalisation;
use App\Entity\Lit;
use App\Entity\Patient;
use App\Entity\StatutHospitalisation;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HospitalisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => function  (Patient $patient) {
                    return $patient->getNom().' - '.$patient->getCode();
                },

                'required' => true,
                'placeholder' => 'Sélctionnez un patient',
                'attr' => [
                    'class' => 'form-control select2-show-search',
                ],
                'query_builder' => function(PatientRepository $patientRepository){
                    
                    return $patientRepository->createQueryBuilder('p')->orderBy('p.nom');
                },
            ])
            ->add('lit', EntityType::class, [
                'class' => Lit::class,
                'choice_label' => function (Lit $lit) {
                    return 'Lit #'. $lit->getNumeroLit().' - Chambre'. $lit->getChambre()->getChambre();
                },
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('l')
                    ->where('l.disponible = 1');
                },
                'required' => true,
                'placeholder' => 'Choisir un lit disponible',
                'attr' => [
                    'class' => 'form-control select2-show-search',
                ],
            ])
            ->add('statut', EntityType::class, [
                'class' => StatutHospitalisation::class,
                'choice_label' => 'statutHospitalisation',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('s')
                    ->where('s.supprime = 0');
                },
                'required' => true,
                'placeholder' => "Choisir un le statut de l'Hospitalisation",
                'attr' => [
                    'class' => 'form-control select2-show-search',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hospitalisation::class,
        ]);
    }
}
