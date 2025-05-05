<?php

namespace App\Form;

use App\Entity\Lot;
use App\Entity\User;
use App\Entity\Produit;
use App\Entity\Specialite;
use App\Repository\SpecialiteRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class BilletDeSessionType extends AbstractType
{
    public function __construct(
        protected TranslatorInterface $translator,
        )
    {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class, [
                'label' => $this->translator->trans("Intitulé du billet de session"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Intitulé du billet de session"),
                ]
            ])
            ->add('prixVente', IntegerType::class, [
                'label' => $this->translator->trans("Prix"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Prix"),
                ]
            ])
            ->add('specialite', EntityType::class, [
                'class' => Specialite::class,
                'choice_label' => 'specialite',
                'required' => true,
                'placeholder' => $this->translator->trans('- - -'),
                'attr' => [
                    'class' => 'form-control select2-show-search',
                ],
                'query_builder' => function(SpecialiteRepository $specialiteRepository){
                    
                    return $specialiteRepository->createQueryBuilder('s')->orderBy('s.specialite');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
