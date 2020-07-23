<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('brand')
            ->add('size', TextType::class, ['label'=>'Contenance en mL'])
            ->add('color')
            ->add('isFavorite')
            ->add('isHated')
            ->add('category', EntityType::class, [
                'class'=> Category::class,
                'choice_label'=>'name'
            ])
            ->add('comment')
            ->add('user', EntityType::class, [
                'class'=> User::class,
                'choice_label'=>'username'
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
