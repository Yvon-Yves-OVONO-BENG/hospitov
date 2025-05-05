<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Ambulance;
use App\Repository\UserRepository;
use App\Entity\AttributionAmbulance;
use App\Repository\AmbulanceRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class AttributionAmbulanceType extends AbstractType
{
    public function __construct(
        protected TranslatorInterface $translator,
        )
    {}
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ambulance', EntityType::class, [
                'class' => Ambulance::class,
                'choice_label' => 'ambulance',
                'required' => true,
                'placeholder' => $this->translator->trans('- - -'),
                'attr' => [
                    'class' => 'form-control select2-show-search',
                ],
                'query_builder' => function(AmbulanceRepository $ambulanceRepository){
                    
                    return $ambulanceRepository->createQueryBuilder('a')->orderBy('a.ambulance');
                },
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom',
                'required' => true,
                'placeholder' => $this->translator->trans('- - -'),
                'attr' => [
                    'class' => 'form-control select2-show-search',
                ],
                'query_builder' => function(UserRepository $userRepository){
                    
                    return $userRepository->findChauffeurs();
                },
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AttributionAmbulance::class,
        ]);
    }
}
