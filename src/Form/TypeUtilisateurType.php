<?php

namespace App\Form;

use App\Entity\TypeUtilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TypeUtilisateurType extends AbstractType
{
    public function __construct(
        protected TranslatorInterface $translator,
        )
    {}
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeUtilisateur', TextType::class, [
                'label' => $this->translator->trans("Type personnel"),
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Type personnel"),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TypeUtilisateur::class,
        ]);
    }
}
