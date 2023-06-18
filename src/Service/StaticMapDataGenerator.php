<?php

namespace App\Service;

use App\Entity\BaseLayer;
use App\Entity\Feature;
use App\Entity\FeatureCategory;
use App\Entity\Game;
use Brick\Geo\Geometry;
use Brick\Geo\IO\GeoJSON\Feature as GeoJsonFeature;
use Brick\Geo\IO\GeoJSONWriter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class StaticMapDataGenerator
{
    const GAMES = 'Games';
    const GAME_CONFIG = 'GameConfig';
    const BASE_LAYERS = 'BaseLayers';
    const CATEGORIES = 'Categories';
    const CATEGORY_TREE = 'CategoryTree';
    const FEATURES = 'Features';

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ParameterBagInterface $bag,
        private readonly SerializerInterface $serializer,
        private readonly string $publicPath
    )
    {
    }

    /**
     * @param Feature[] $features
     */
    public static function featureToGeojson(array $features): array
    {
        $writer = new GeoJSONWriter();
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

    public function getFiles(?string $filenamePrefix = null, ?Game $game = null): Finder
    {
        $rootDir = sprintf('%s/public%s', $this->bag->get('kernel.project_dir'), $this->publicPath);
        $buildDir = $rootDir . '/builds';
        if(!is_null($game) && self::GAMES !== $filenamePrefix){
            $filenamePrefix .= '-' . $game->getShortName();
        }
        return $this->findBuildFiles($buildDir, $filenamePrefix)->sortByName()->reverseSorting();
    }

    public function generateGames(): void
    {
        $this->generateFile(
            $this->em->getRepository(Game::class)->findByEnabled(true),
            self::GAMES,
            'game:read'
        );
    }

    public function generateConfig(Game $game): void
    {
        $this->generateFile(
            $game->getGameConfig(),
            sprintf('%s-%s', self::GAME_CONFIG, $game->getShortName()),
            'config:read'
        );
    }

    public function generateBaseLayers(Game $game): void
    {
        $this->generateFile(
            $this->em->getRepository(BaseLayer::class)->findByGameEnabledOrdered($game),
            sprintf('%s-%s', self::BASE_LAYERS, $game->getShortName()),
            'layer:read'
        );
    }

    public function generateCategories(Game $game): void
    {
        $this->generateFile(
            $this->em->getRepository(FeatureCategory::class)->findBy(['enabled' => true, 'game' => $game]),
            sprintf('%s-%s', self::CATEGORIES, $game->getShortName()),
            'category:read');
    }

    public function generateCategoryTree(Game $game): void
    {
        $this->generateFile(
            $this->em->getRepository(FeatureCategory::class)->findByGameEnabledOrderedRoot($game),
            sprintf('%s-%s', self::CATEGORY_TREE, $game->getShortName()),
            'category:read:tree'
        );
    }

    public function generateFeatures(Game $game): void
    {
        $this->generateFile(
            self::featureToGeojson(
                $this->em->getRepository(Feature::class)->findByGameEnabled($game)
            ),
            sprintf('%s-%s', self::FEATURES, $game->getShortName())
        );
    }

    public function generateFile($data, string $filenamePrefix, string|array|null $groups = null): void
    {
        $json = $this->serialize($data, $groups);
        $this->saveFile($json, $filenamePrefix);
    }

    private function serialize($data, string|array|null $groups): string
    {
        if(is_string($groups)){
            $groups = [$groups];
        }
        return $this->serializer->serialize(
            $data,
            'json',
            is_null($groups) ? [] : [AbstractNormalizer::GROUPS => $groups]
        );
    }

    private function saveFile(string $data, string $filenamePrefix, ?bool $deletePrevious = true): void
    {
        $fs = new Filesystem();
        $rootDir = sprintf('%s/public%s', $this->bag->get('kernel.project_dir'), $this->publicPath);
        $buildDir = $rootDir . '/builds';
        $buildDate = (new \DateTimeImmutable())->format('YmdHis');
        $filename = sprintf('%s-%s.js', $filenamePrefix, $buildDate);
        $fullPath = $buildDir . '/' . $filename;

        !$fs->exists($rootDir) && $fs->mkdir($rootDir);

        !$fs->exists($buildDir) && $fs->mkdir($buildDir);

        if($deletePrevious) {
            /** @var SplFileInfo $file */
            foreach ($this->findBuildFiles($buildDir, $filenamePrefix) as $file) {
                $fs->remove($file);
            }
        }
        $fs->dumpFile($fullPath, sprintf('window.%s = %s;', lcfirst(explode('-', $filenamePrefix)[0]), $data));
        $symlink = sprintf('%s/%s.js', $rootDir, $filenamePrefix);

        $fs->exists($symlink) && $fs->remove($symlink);

        $fs->symlink($fs->makePathRelative($buildDir, $rootDir) . $filename, $symlink);
    }

    private function findBuildFiles(string $dir, ?string $filenamePrefix = null): Finder
    {
        $finder = new Finder();
        $finder->files()->in($dir);
        if(!is_null($filenamePrefix)){
            $finder->name(sprintf('%s-*.js', $filenamePrefix));
        } else {
            $finder->name('*.js');
        }
        return $finder;
    }
}