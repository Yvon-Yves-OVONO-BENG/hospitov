<?php

namespace App\Form;

use App\Entity\Niveau;
use App\Entity\Chambre;
use App\Entity\Batiment;
use App\Entity\Specialite;
use App\Entity\TypeChambre;
use App\Repository\NiveauRepository;
use App\Repository\BatimentRepository;
use App\Repository\SpecialiteRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\TypeChambreRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ChambreType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator,
        )
    {}
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('chambre', TextType::class, [
                'label' => $this->translator->trans("Nom de la chambre"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Nom de la chambre"),
                ]
            ])
            ->add('typeChambre', EntityType::class, [
                'class' => TypeChambre::class,
                'choice_label' => 'typeChambre',
                'required' => true,
                'placeholder' => $this->translator->trans('- - -'),
                'attr' => [
                    'class' => 'form-control select2-show-search',
                ],
                'query_builder' => function(TypeChambreRepository $typeChambreRepository){
                    
                    return $typeChambreRepository->createQueryBuilder('t')->orderBy('t.typeChambre');
                },
            ])
            ->add('niveau', EntityType::class, [
                'class' => Niveau::class,
                'choice_label' => 'niveau',
                'required' => true,
                'placeholder' => $this->translator->trans('- - -'),
                'attr' => [
                    'class' => 'form-control select2-show-search',
                ],
                'query_builder' => function(NiveauRepository $niveauRepository){
                    
                    return $niveauRepository->createQueryBuilder('n')->orderBy('n.id');
                },
            ])
            ->add('batiment', EntityType::class, [
                'class' => Batiment::class,
                'choice_label' => 'batiment',
                'required' => true,
                'placeholder' => $this->translator->trans('- - -'),
                'attr' => [
                    'class' => 'form-control select2-show-search',
                ],
                'query_builder' => function(BatimentRepository $batimentRepository){
                    
                    return $batimentRepository->batimentEnService();
                },
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
                    
                    return $specialiteRepository->specialitePasSupprime();
                },
            ])
            ->add('nombreLit', NumberType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'min' => 1,
                    'class' => "form-control",
                    'placeholder' => $this->translator->trans("Le nombre de lit doit être >= à 1"),
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => $this->translator->trans("Le nombre de lit doit être >= à 1"),
                    ]),
                    new Assert\PositiveOrZero(),
                    new Assert\GreaterThanOrEqual([
                        'value' => 1,
                        'message' => $this->translator->trans("Le nombre de lit doit être >= à 1 "),
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chambre::class,
        ]);
    }
}
