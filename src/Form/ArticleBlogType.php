<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\ArticleBlog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticleBlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category' , EntityType::class ,[
                'class'=>Category::class,
                'choice_label' => 'name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please choose a category',
                    ]),
                ]
            ])
            ->add('articleTitle',TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a word',
                    ]),
                ]
            ])
            ->add('articleDescription',TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a word',
                    ]),
                ]
            ])
            ->add('articleContent',TextareaType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a word',
                    ]),
                ]
            ])
            ->add('image',FileType::class,[
                'label'=>false,
                'multiple'=>true,
                'required'=>false,
                'mapped'=>false

            ])
            ->add('submit',SubmitType::class,[
                'label'=>'Envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ArticleBlog::class,
        ]);
    }
}
