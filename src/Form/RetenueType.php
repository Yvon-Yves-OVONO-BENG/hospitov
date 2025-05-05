<?php

namespace App\Form;

use App\Entity\Retenue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RetenueType extends AbstractType
{
    public function __construct(
        protected TranslatorInterface $translator
        )
    {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('retenue', TextType::class, [
                'label' => $this->translator->trans("Intitulé de la retenue"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Intitulé du retenue"),
                ]
            ])
            ->add('pourcentage', TextType::class, [
                'label' => $this->translator->trans("Pourcentage de la retenue"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Ex : 10 pour 10%"),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Retenue::class,
        ]);
    }
}
