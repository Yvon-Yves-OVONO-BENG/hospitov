<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Patient;
use App\Entity\ParametresVitaux;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ParametresVitauxType extends AbstractType
{
    public function __construct(
        protected TranslatorInterface $translator,
        )
    {}
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('poids', TextType::class, [
                'label' => $this->translator->trans("Poids"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Poids du patient"),
                ]
            ])
            ->add('temperature', TextType::class, [
                'label' => $this->translator->trans("Température"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Température du patient"),
                ]
            ])
            ->add('tension', TextType::class, [
                'label' => $this->translator->trans("Tension"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("tension du patient"),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ParametresVitaux::class,
        ]);
    }
}
