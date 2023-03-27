<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class UsersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Users::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
             ->setEntityLabelInPlural('Utilisateurs')
             ->setPageTitle('index', 'NWM-Administration des utilisateurs')
             ->setEntityLabelInSingular('Utilisateur');
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
               ->hideOnForm(),
            TextField::new('Pseudo'),
            TextField::new('Firstname'),
            TextField::new('Lastname'),
            TextField::new('email')
            ->setFormTypeOption('disabled','disabled'),
            TextField::new('imageName'),
            ArrayField::new('roles')
            ->hideOnIndex(),
            DateTimeField::new('updatedAt')
            ->hideOnForm()
            
        ];
    }
    
}
