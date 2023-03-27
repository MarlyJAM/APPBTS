<?php

namespace App\Controller\Admin;

use App\Entity\Answer;
use App\Entity\ArticleBlog;
use App\Entity\Questions;
use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        #return parent::index();

        
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('NWM-Admin')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', Users::class);
        yield MenuItem::linkToCrud('Questions', 'fa fa-question', Questions::class);
        yield MenuItem::linkToCrud('Réponses', 'fa fa-reply',Answer::class);
        yield MenuItem::linkToCrud('Articles', 'fa-solid fa-newspaper',ArticleBlog::class);
    
    }
}
