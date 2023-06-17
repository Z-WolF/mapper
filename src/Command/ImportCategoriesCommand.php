<?php

namespace App\Command;

use App\Entity\FeatureCategory;
use App\Entity\Game;
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
    name: 'app:import:categories',
    description: 'Add a short description for your command',
)]
class ImportCategoriesCommand extends Command
{
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

        $cats = $this->serializer->decode(file_get_contents($filename), 'json');
        usort($cats, fn($a, $b) => $a['id'] <=> $b['id']);
        $categories = [];
        $io->progressStart(count($cats));
        $game = $this->em->getRepository(Game::class)->find(1);
        foreach ($cats as $cat) {
            $category = new FeatureCategory();
            $category
                ->setName($cat['name'])
                ->setMinZoom($cat['visibleZoom'])
                ->setLabel(3 == $cat['markerCategoryTypeId'])
                ->setColor(empty($cat['color']) ? null : $cat['color'])
                ->setParent($categories[$cat['parentId']] ?? null)
                ->setGame($game)
                ->setOldId($cat['id'])
                ;
            $categories[$cat['id']] = $category;
            $this->em->persist($category);
            $io->progressAdvance();
        }
        $this->em->flush();
        $io->progressFinish();

        $io->success('OK!');

        return Command::SUCCESS;
    }
}
