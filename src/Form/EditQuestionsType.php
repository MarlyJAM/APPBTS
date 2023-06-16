<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Questions;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EditQuestionsType extends AbstractType
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
            ->add('mainTitle',TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a word',
                    ]),
                ]
            ])
            ->add('description',TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a word',
                    ]),
                ]
            ])
            ->add('content',CKEditorType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a word',
                    ]),
                   
                ]
            ])
            ->add('imageFile',VichImageType::class,[
                'label'=>'Importer une image?',
                'required'=>false,
            ])

            ->add('Soumettre', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Questions::class,
        ]);
    }
}
