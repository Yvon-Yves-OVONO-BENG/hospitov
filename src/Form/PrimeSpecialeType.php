<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\PrimeSpeciale;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PrimeSpecialeType extends AbstractType
{
    public function __construct(
        protected TranslatorInterface $translator
        )
    {}
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class, [
                'label' => $this->translator->trans("Intitulé de la prime"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Intitulé de la prime"),
                ]
            ])
            ->add('montant', TextType::class, [
                'label' => $this->translator->trans("Montant du gain"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Montant"),
                ]
            ])
            ->add('personnel', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom',
                'required' => true,
                'placeholder' => $this->translator->trans('Sélectionner un personnel'),
                'attr' => [
                    'class' => 'form-control select2-show-search',
                ],
                'query_builder' => function(UserRepository $userRepository){
                    
                    return $userRepository->getUserPrimeSpeciale();
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PrimeSpeciale::class,
        ]);
    }
}
