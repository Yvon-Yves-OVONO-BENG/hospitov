<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class EchographieType extends AbstractType
{
    public function __construct(
        protected TranslatorInterface $translator,
        )
    {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class, [
                'label' => $this->translator->trans("Intitulé de l'Echographie"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Intitulé de l'Echographie"),
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
