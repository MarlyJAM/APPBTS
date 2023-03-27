<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UpdateUsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Pseudo',TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a word',
                    ]),
                ]
            ]
            )
            ->add('Lastname',TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a word',
                    ]),
                ]
            ])
            ->add('Firstname',TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a word',
                    ]),
                ]
            ])
            ->add('plainPassword',TextType::class,[
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
            ->add('Modifier', SubmitType::class)
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
