<?php

namespace App\Form;

use App\Entity\CategoriePermisDeConduire;
use App\Entity\User;
use App\Entity\Genre;
use App\Entity\Specialite;
use App\Entity\TypeUtilisateur;
use App\Repository\CategoriePermisDeConduireRepository;
use App\Repository\GenreRepository;
use App\Repository\SpecialiteRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\TypeUtilisateurRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UtilisateurType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator,
        private TokenStorageInterface $tokenStorage
        )
    {}
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => $this->translator->trans("Nom utilisateur"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Nom utilisateur"),
                ]
            ])
           
            ->add('nom', TextType::class, [
                'label' => $this->translator->trans("Nom complet"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Nom complet de l'utilisateur"),
                ]
            ])
            ->add('contact', TextType::class, [
                'label' => $this->translator->trans("Contact"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Contact utilisateur"),
                ]
            ])
            // ->add('password', PasswordType::class, [
            //     'label' => $this->translator->trans("Mot de passe"),
            //     'required' => false,
            //     'attr' => [
            //         'class' => 'form-control',
            //         'placeholder' => $this->translator->trans("Mot de passe"),
            //     ]
            // ])
            ->add('adresse', TextType::class, [
                'label' => $this->translator->trans("Adresse"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Votre adresse"),
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => $this->translator->trans("Email"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Votre email"),
                ]
            ])

            ->add('typeUtilisateur', EntityType::class, [
                'class' => TypeUtilisateur::class,
                'choice_label' => 'typeUtilisateur',
                'required' => true,
                'placeholder' => $this->translator->trans('- - -'),
                'attr' => [
                    'class' => 'form-control select2-show-search',
                ],
                'query_builder' => function(TypeUtilisateurRepository $typeUtilisateurRepository){
                    
                    return $typeUtilisateurRepository->createQueryBuilder('t')->orderBy('t.typeUtilisateur');
                },
            ])
            ->add('genre', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'genre',
                'required' => true,
                'placeholder' => $this->translator->trans('- - -'),
                'attr' => [
                    'class' => 'form-control',
                ],
                'query_builder' => function(GenreRepository $genreRepository){
                    
                    return $genreRepository->createQueryBuilder('g')->orderBy('g.genre');
                },
            ])
            ->add('categoriePermisDeConduire', EntityType::class, [
                'class' => CategoriePermisDeConduire::class,
                'choice_label' => 'categoriePermisDeConduire',
                'required' => false,
                'placeholder' => $this->translator->trans('- - -'),
                'attr' => [
                    'class' => 'form-control select2-show-search',
                ],
                'query_builder' => function(CategoriePermisDeConduireRepository $categoriePermisDeConduireRepository){
                    
                    return $categoriePermisDeConduireRepository->createQueryBuilder('c')->orderBy('c.categoriePermisDeConduire');
                },
            ])
            ->add('numeroPermisDeConduire', TextType::class, [
                'label' => $this->translator->trans("Numéro permis de conduire"),
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Numéro permis"),
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
            'data_class' => User::class,
        ]);
    }
}
