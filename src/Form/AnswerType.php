<?php

namespace App\Form;

use App\Entity\Answer;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content',CKEditorType::class,[
             'label'=>'Ecrivez une réponse',
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
            'data_class' => Answer::class,
        ]);
    }
}
