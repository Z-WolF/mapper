<?php

namespace App\Controller;

use App\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    #[Route('/map/{slug}', name: 'app_map')]
    public function index(Game $game): Response
    {
        return $this->render('map/index.html.twig', [
            'game' => $game,
        ]);
    }
}
