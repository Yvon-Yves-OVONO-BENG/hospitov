<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class BulletinSalaireType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator
    ){}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('personnel', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom',
                'label' => $this->translator->trans('Personnel'),
            ])
            ->add('mois', ChoiceType::class, [
                'choices' => [
                    'Janvier' => 1,
                    'Février' => 2,
                    'Mars' => 3,
                    'Avril' => 4,
                    'Mai' => 5,
                    'Juin' => 6,
                    'Juillet' => 7,
                    'Août' => 8,
                    'Septembre' => 9,
                    'Octobre' => 10,
                    'Novembre' => 11,
                    'Décembre' => 12,
                ],
                'label' => 'Mois'
            ])
            ->add('annee', ChoiceType::class, [
                'choices' => [
                    2023 => 2023,
                    2024 => 2024,
                    2025 => 2025,
                ],
                'label' => 'Année'
            ])
            // ->add('submit', SubmitType::class, [
            //     'label' => $this->translator->trans('Générer le bulletin')
            // ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
