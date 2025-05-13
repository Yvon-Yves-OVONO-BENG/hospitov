<?php

namespace App\Form;

use App\Entity\Gain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GainType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator
        )
    {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gain', TextType::class, [
                'label' => $this->translator->trans("Intitulé du gain"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Intitulé du gain"),
                ]
            ])
            ->add('pourcentage', TextType::class, [
                'label' => $this->translator->trans("Pourcentage du gain"),
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
            'data_class' => Gain::class,
        ]);
    }
}
