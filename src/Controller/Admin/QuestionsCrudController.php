<?php

namespace App\Controller\Admin;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use App\Entity\Questions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use phpDocumentor\Reflection\Types\Boolean;

class QuestionsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Questions::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
             ->setEntityLabelInPlural('Questions')
             ->setPageTitle('index', 'NWM-Administration des questions')
             ->setEntityLabelInSingular('Question');
             
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->hideOnForm(),
            TextField::new('mainTitle')
            ->setFormTypeOption('disabled','disabled'),
            TextareaField::new('description')
            ->setFormTypeOption('disabled','disabled'),
            TextareaField::new('content')
            ->setFormType(TextareaType::class)
            ->setFormTypeOption('disabled','disabled'),
            TextField::new('imageName')
            ->setFormTypeOption('disabled','disabled'),
            TextField::new('author.pseudo')
            ->setFormTypeOption('disabled','disabled'),
            DateField::new('createdAt')
            ->hideOnForm(),
            DateField::new('updatedAt')
            ->setFormTypeOption('disabled','disabled'),
            BooleanField::new('isVerified'),
        
        ];
    }
    
}
