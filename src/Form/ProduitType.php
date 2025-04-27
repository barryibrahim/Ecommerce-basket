<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,['attr' => ['class'=> 'form-control'], 'label_attr' => ['class'=>
            'fw-bold']])
            ->add('description',TextareaType::class,['attr' => ['class'=> 'form-control', 'rows'=>'1', 'cols'
            => '1'], 'label_attr' => ['class'=> 'fw-bold']])
            ->add('prix',NumberType::class,[
                'attr' => ['class' => 'form-control col-md-4'], 
                'label_attr' => ['class' => 'fw-bold'],
                'label' => 'Prix',
            ])
            ->add('stock',NumberType::class,[
                'attr' => ['class' => 'form-control col-md-4'], 
                'label_attr' => ['class' => 'fw-bold'],
                'label' => 'Stock disponible',
            ])
            
            ->add('envoyer', SubmitType::class,['attr' => ['class'=> 'btn bg-primary text-white m-4' ],
            'row_attr' => ['class' => 'text-center'],])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
