<?php

namespace App\Controller;

use App\Entity\Feature;
use App\Entity\Game;
use App\Repository\BaseLayerRepository;
use App\Repository\FeatureCategoryRepository;
use App\Repository\FeatureRepository;
use App\Repository\GameRepository;
use Brick\Geo\Geometry;
use Brick\Geo\IO\GeoJSON\Feature as GeoJsonFeature;
use Brick\Geo\IO\GeoJSONWriter;
use Jsor\Doctrine\PostGIS\Functions\ST_GeomFromGeoJSONTest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MapController extends AbstractController
{
    #[Route('/map/{slug}', name: 'app_map')]
    public function index(
        Game $game,
        GameRepository $gr,
        BaseLayerRepository $blr,
        FeatureRepository $fr,
        FeatureCategoryRepository $fcr,
    ): Response
    {
        return $this->render('map/index.html.twig', [
            'games' => $gr->findByEnabled(true),
            'game' => $game,
            'baseLayers' => $blr->findByGameEnabledOrdered($game),
            'categoryTree' => $fcr->findByGameEnabledOrderedRoot($game),
            'categories' => $fcr->findBy([
                'enabled' => true,
                'game' => $game
            ]),
            'features' => $this->featureToGeojson($fr->findByGameEnabled($game)),
        ]);
    }

    /**
     * @param Feature[] $feature
     */
    private function featureToGeojson(array $features, GeoJSONWriter $writer = new GeoJSONWriter()): array
    {
        $gjfs = [];
        foreach ($features as $feature) {
            $gjfs[] = $writer->writeRaw(new GeoJsonFeature(
                Geometry::fromText($feature->getGeometry()),
                (object)[
                    'id' => $feature->getId(),
                    'name' => $feature->getName(),
                    'category' => $feature->getCategory()->getId(),
                    'description' => $feature->getDescription(),
                    'baseLayer' => $feature->getBaseLayer()->getId(),
                ]
            ));
        }
        return $gjfs;
    }
}
