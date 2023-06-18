<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\BaseLayerRepository;
use App\Repository\FeatureCategoryRepository;
use App\Repository\FeatureRepository;
use App\Repository\GameRepository;
use App\Service\StaticMapDataGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    #[Route('/map/{slug}', name: 'app_map')]
    public function index(
        Game $game,
        GameRepository $gr,
        BaseLayerRepository $blr,
        FeatureRepository $fr,
        FeatureCategoryRepository $fcr
    ): Response
    {
        return $this->render('map/index.html.twig', [
            'game' => $game,
        ]);
    }
}
