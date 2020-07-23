<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label'=> 'Nom'
            ])
            ->add('brand',TextType::class, [
                'label'=> 'Marque'
            ])
            ->add('size', TextType::class, ['label'=>'Contenance en mL'])
            ->add('color', TextType::class, [
                'label'=> 'Couleur',
                'required'=>false
            ])
            ->add('category', EntityType::class, [
                'class'=> Category::class,
                'choice_label'=>'name',
                'label'=>'Catégorie',
                'attr'=>[
                    'class'=>'custom-select'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
