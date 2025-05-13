<?php

namespace App\Form;

use App\Entity\Chambre;
use App\Entity\Lit;
use App\Repository\ChambreRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class LitType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator,
        )
    {}
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numeroLit', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans("Numéro du lit"),
                ]
            ])
            ->add('chambre', EntityType::class, [
                'class' => Chambre::class,
                'choice_label' => 'chambre',
                'required' => true,
                'placeholder' => $this->translator->trans('- - -'),
                'attr' => [
                    'class' => 'form-control select2-show-search',
                ],
                'query_builder' => function(ChambreRepository $chambreRepository){
                    
                    return $chambreRepository->createQueryBuilder('c')->where('c.supprime = 0')->andWhere('c.enService = 1')->orderBy('c.chambre');
                },
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lit::class,
        ]);
    }
}
