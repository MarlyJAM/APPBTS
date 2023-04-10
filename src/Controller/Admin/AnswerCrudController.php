<?php

namespace App\Controller\Admin;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use phpDocumentor\Reflection\Types\Boolean;
use App\Entity\Answer;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AnswerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Answer::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
             ->setEntityLabelInPlural('Réponses')
             ->setPageTitle('index', 'NWM-Administration des réponses')
             ->setEntityLabelInSingular('Réponse');
             
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->hideOnForm(),
            TextareaField::new('content')
            ->setFormType(TextareaType::class)
            ->setFormTypeOption('disabled','disabled'),
            TextField::new('imageName')
            ->setFormTypeOption('disabled','disabled'),
            TextareaField::new('question.content')
            ->setFormType(TextareaType::class)
            ->setFormTypeOption('disabled','disabled'),
            TextField::new('auth.pseudo')
            ->setFormTypeOption('disabled','disabled'),
            DateField::new('createdAt')
            ->hideOnForm(),
            DateField::new('updatedAt')
            ->setFormTypeOption('disabled','disabled'),
            BooleanField::new('isVerified'),
        
        ];
    }
    
}
