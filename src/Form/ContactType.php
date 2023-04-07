<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullname',TextType::class,[
                'label'=>"Nom-Prenom",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a word',
                    ]),
               
                   
                ]
            ])
            ->add('email',EmailType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a word',
                    ]),
                   
                ]
            ])
            ->add('subject',TextType::class,[
                'label'=>"Sujet",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a word',
                    ]),
                   
                ]
            ])
            ->add('message',TextareaType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a word',
                    ]),
                   
                ]
            ])
            ->add('agreeTerms',CheckboxType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please check box',
                    ]),
                   
                ]
            ])
            ->add('submit',SubmitType::class,[
                'label'=>'Envoyer'
            ])
            /*->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(['message' => 'There were problems with your captcha. Please try again or contact with support and provide following code(s): {{ errorCodes }}']),
                'action_name' => 'contact',
                'locale' => 'fr',
            ]);*/
          
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
