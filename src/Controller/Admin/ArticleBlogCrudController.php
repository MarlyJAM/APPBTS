<?php

namespace App\Controller\Admin;

use App\Entity\ArticleBlog;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleBlogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ArticleBlog::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
             ->setEntityLabelInPlural('Articles')
             ->setPageTitle('index', 'NWM-Administration des articles')
             ->setEntityLabelInSingular('Article')
             ->addFormTheme( '@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->hideOnForm(),
            TextField::new('articleTitle')
            ->setFormTypeOption('disabled','disabled'),
            TextareaField::new('articleDescription')
            ->setFormTypeOption('disabled','disabled'),
            TextareaField::new('articleContent')
            ->setFormType(CKEditorType::class)
            ->setFormTypeOption('disabled','disabled'),
            TextField::new('article_auth.pseudo')
            ->setFormTypeOption('disabled','disabled'),
            DateField::new('createdAt')
            ->hideOnForm(),
            DateField::new('updatedAt')
            ->setFormTypeOption('disabled','disabled'),
            BooleanField::new('isPublished'),
        
        ];
    }
    
}
