<?php

namespace App\Command;

use App\Entity\BaseLayer;
use App\Entity\Feature;
use App\Entity\FeatureCategory;
use App\Entity\Game;
use Brick\Geo\LineString;
use Brick\Geo\Point;
use Doctrine\DBAL\Driver\IBMDB2\Exception\CannotCreateTemporaryFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(
    name: 'app:import:features',
    description: 'Add a short description for your command',
)]
class ImportMarkersCommand extends Command
{
    const BASELAYER = [
        '2101' => '3',
        '2102' => '2',
        '2103' => '4',
        '19' => '5'
    ];

    /**
     * @var BaseLayer[]
     */
    private $baseLayers = [];

    /**
     * @var FeatureCategory[]
     */
    private $categories = [];

    public function __construct(private readonly EntityManagerInterface $em, private readonly SerializerInterface $serializer, string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'Argument description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filename = $input->getArgument('file');
        if(empty($filename)){
            $io->error('Empty file!');
            return 1;
        }

        $marks = json_decode(file_get_contents($filename), true);
        usort($marks, fn($a, $b) => $a['id'] <=> $b['id']);
        $features = [];
        $io->progressStart(count($marks));
        foreach ($marks as $mark) {
            $feature = new Feature();
            $feature
                ->setName($mark['name'])
                ->setDescription(empty($mark['tabText']) ? null : $mark['tabText'])
                ->setBaseLayer($this->getBaselayer($mark['mapId']))
                ->setCategory($this->getCategory($mark['markerCategoryId']))
                ;
            if(!empty($mark['path'])){
                $feature->setGeometry($this->createLine($mark['path'])->asText());
            } else {
                $feature->setGeometry(Point::xy($mark['x'], $mark['y'])->asText());
            }
            $this->em->persist($feature);
            $io->progressAdvance();
        }
        $this->em->flush();
        $io->progressFinish();

        $io->success('OK!');

        return Command::SUCCESS;
    }

    private function getBaselayer($id): BaseLayer
    {
        if(!isset($this->baseLayers[$id])){
            $this->baseLayers[$id] = $this->em->getRepository(BaseLayer::class)->find(self::BASELAYER[$id]);;
        }

        return $this->baseLayers[$id];
    }

    private function getCategory($id): FeatureCategory
    {
        if(!isset($this->categories[$id])){
            $this->categories[$id] = $this->em->getRepository(FeatureCategory::class)->findOneByOldId($id);
        }

        return $this->categories[$id];
    }

    private function createLine(string $json): LineString
    {
        $path = json_decode($json, true);

        $points = [];
        foreach ($path as $segment) {
            $points[] = Point::xy($segment['lng'], $segment['lat']);
        }
        return LineString::of(...$points);
    }
}
