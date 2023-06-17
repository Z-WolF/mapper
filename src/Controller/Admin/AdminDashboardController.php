<?php

namespace App\Controller\Admin;

use App\Entity\BaseLayer;
use App\Entity\FeatureCategory;
use App\Entity\Game;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
//        return parent::index();

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
         return $this->render('admin/blank.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mapper');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Home', 'fa fa-home');
        yield MenuItem::linkToCrud('Games', 'fa fa-gamepad', Game::class);
        yield MenuItem::linkToCrud('Base Layers', 'fa fa-layer-group', BaseLayer::class);
        yield MenuItem::linkToCrud('Feature Categories', 'fa fa-map-location-dot', FeatureCategory::class);
        yield MenuItem::section('Management');
        yield MenuItem::linkToCrud('Users', 'fa fa-users', User::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
