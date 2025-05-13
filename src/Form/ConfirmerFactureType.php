<?php

namespace App\Form;

use App\Entity\Facture;
use App\Entity\Patient;
use App\Entity\ModePaiement;
use App\Repository\PatientRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use App\Repository\ModePaiementRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ConfirmerFactureType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator,
        private PatientRepository $patientRepository,
        private ModePaiementRepository $modePaiementRepository
        )
    {}
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $netApayer = $options['netApayer'];
        $builder
            ->add('nomPatient', TextType::class, [
                'label' => $this->translator->trans("Nom pour la facture"),
                'required' => false,
                'attr' => [
                    'class' => "form-control",
                    'placeholder' => $this->translator->trans("Nom pour la facture"),
                    
                ]
            ])
            ->add('contactPatient', TextType::class, [
                'label' => $this->translator->trans("Numéro de téléphone"),
                'required' => false,
                'attr' => [
                    'class' => "form-control",
                    'placeholder' => $this->translator->trans("Numéro de téléphone"),
                ]
            ])
            ->add('avance', NumberType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'min' => 0,
                    'max' => $netApayer,
                    'class' => "form-control",
                    'placeholder' => $this->translator->trans("L'avance doit être >= 0 ou <= à ").number_format($netApayer, 0, '', ' '),
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => $this->translator->trans("L'avance ne peut pas être vide!"),
                    ]),
                    new Assert\PositiveOrZero(),
                    new Assert\LessThanOrEqual([
                        'value' => $netApayer,
                        'message' => $this->translator->trans("L'avance doit être inférieure ou égale à ").number_format($netApayer, 0, '', ' ')." ! ",
                    ])
                ]
            ])
            ->add('modePaiement', EntityType::class, [
                'class' => ModePaiement::class,
                'choice_label' => 'modePaiement',
                'required' => true,
                'placeholder' => $this->translator->trans('Choisir un mode de paiement'),
                'attr' => [
                    'class' => 'form-control select2-show-search',
                ],
                'query_builder' => function(ModePaiementRepository $modePaiementRepository){
                    
                    return $modePaiementRepository->createQueryBuilder('m')->orderBy('m.modePaiement');
                },
            ])
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => function (Patient $patient) {
                    return $patient->getNom()." - ".$patient->getCode();
                },
                'required' => false,
                'placeholder' => $this->translator->trans('Sélectionner un patient'),
                'attr' => [
                    'class' => 'form-control select2-show-search',
                    'value' => 'Client',
                ],
                'query_builder' => function(PatientRepository $patientRepository){
                    
                    return $patientRepository->createQueryBuilder('p')->andWhere('p.termine = 0')->orderBy('p.nom');
                }
            ]);

            // $builder->get('patient')->addEventListener(
            //     FormEvents::POST_SUBMIT,
            //     function (FormEvent $event) {
            //         $form = $event->getForm();
            //         $patient = $form->getData();

            //         if ($patient) {
            //             $form->getParent()->get('contactPatient')->setData($patient->getTelephone());
            //         }
            //     }
            // );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Facture::class
        ]);

        $resolver->setRequired('netApayer');
    }
}
