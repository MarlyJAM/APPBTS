<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('plainPassword',RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type'=>PasswordType::class,
                'first_options'=>[ 
                    'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder'=>'Mot de passe'
                ],
                'label'=> 'Mot de passe actuel',
                ],

                'second_options'=>[ 
                    'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder'=>'Mot de passe'
                ],
                'label'=> 'Saisissez à nouveau le mot de passe'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a word',
                    ]),
                ]
               
            ])
            ->add('newPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label'=> 'Nouveau mot de passe ',
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder'=>'Mot de passe'
                    
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a word',
                    ]),
                ]
    
            ])
            ->add('Modifier', SubmitType::class)
           
        ;
    }

   
}
