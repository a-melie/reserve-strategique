<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Product;
use App\Repository\CommentRepository;
use App\Repository\ProductRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    private $commentRepository;
    private $productRepository;

    public function __construct(CommentRepository $commentRepository, ProductRepository $productRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->productRepository = $productRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class, [
                'class'=>Product::class,
                'label'=>'Produit',
                'choice_label'=>function ($product) {
                    return $product->getName() . ' | ' . $product->getCategory()->getName() . ' | ' .$product->getBrand() ;}
            ])
            ->add('rate', IntegerType::class, [
                'label' => 'Note sur 5',
                'attr' => ['min' => 1, 'max' => 5, 'step' => 1]])
            ->add('content', TextareaType::class, [
                'label'=> 'Message'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
