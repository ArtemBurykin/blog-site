<?php

namespace App\Controller\Admin;

use App\Entity\Blog\Category;
use App\Entity\Blog\Post;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', []);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('The admin panel');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Blog categories', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Blog posts', 'fas fa-list', Post::class);
    }
}
