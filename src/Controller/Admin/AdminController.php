<?php

namespace App\Controller\Admin;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\NightOut;
use App\Entity\Place;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use function ProxyManager\ProxyGenerator\Util\PublicScopeSimulator;

class AdminController extends AbstractDashboardController
{
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
       //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
              ->setTitle('HangOut');
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user); // TODO: Change the autogenerated stub
            //>displayUserAvatar(false);

    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToUrl('HangOut', 'fas fa-list', 'http://localhost:8001');

        yield MenuItem::section('Users');

        yield MenuItem::subMenu('Actions User', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Create Users', 'fas fa-plus', User::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Users', 'fas fa-eye', User::class)
        ]);

        yield MenuItem::subMenu('Action Campus', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Create Campus', 'fas fa-plus', Campus::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Campus', 'fas fa-eye', Campus::class)
        ]);

        yield MenuItem::subMenu('Action NightOut', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Create NightOut', 'fas fa-plus', NightOut::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show NightOut', 'fas fa-eye', NightOut::class)
        ]);

        yield MenuItem::subMenu('Action Places', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Create Place', 'fas fa-plus', Place::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Place', 'fas fa-eye', Place::class)
        ]);

        yield MenuItem::subMenu('Action City', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Create City', 'fas fa-plus', City::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show City', 'fas fa-eye', City::class)
        ]);

    }

}
