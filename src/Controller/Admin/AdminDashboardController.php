<?php

namespace App\Controller\Admin;

use App\Entity\BaseLayer;
use App\Entity\Feature;
use App\Entity\FeatureCategory;
use App\Entity\Game;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Service\StaticMapDataGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractDashboardController
{
    public function __construct(private readonly GameRepository $gr, private readonly StaticMapDataGenerator $smdg)
    {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('dashboard/admin.html.twig', [
            'gamefiles' => $this->smdg->getFiles()
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mapper');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Home', 'fa fa-home');
        yield MenuItem::linkToUrl('Back to Landing', 'fa fa-arrow-left', '/');
        yield MenuItem::section('Maps');
        /** @var Game $game */
        foreach ($this->gr->findByEnabled(true) as $game) {
            yield MenuItem::linkToUrl($game, 'fa fa-map', $this->generateUrl('app_map', ['slug' => $game->getSlug()]));
        }
        yield MenuItem::section('CRUDs');
        yield MenuItem::linkToCrud('Games', 'fa fa-gamepad', Game::class);
        yield MenuItem::linkToCrud('Base Layers', 'fa fa-layer-group', BaseLayer::class);
        yield MenuItem::linkToCrud('Feature Categories', 'fa fa-map-location-dot', FeatureCategory::class);
        yield MenuItem::linkToCrud('Features', 'fa fa-location-dot', Feature::class);
        yield MenuItem::section('Management');
        yield MenuItem::linkToCrud('Users', 'fa fa-users', User::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->showEntityActionsInlined()
        ;
    }

    #[Route('/admin/static-files', name: 'admin_static')]
    public function generateAllFiles(StaticMapDataGenerator $smdg, GameRepository $gr): Response
    {
        $dts = (new \DateTimeImmutable())->format('YmdHis');
        $smdg->setDateTimeString($dts);
        $smdg->generateGames();
        $games = $gr->findByEnabled(true);
        foreach ($games as $game) {
            $smdg->generateConfig($game);
            $smdg->generateBaseLayers($game);
            $smdg->generateCategories($game);
            $smdg->generateCategoryTree($game);
            $smdg->generateFeatures($game);
            $game->getGameConfig()->setCurrentAssetDateTimeString($dts);
            $gr->save($game, true);
        }
        return $this->redirectToRoute('admin');
    }
}
