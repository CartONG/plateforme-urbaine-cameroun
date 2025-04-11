<?php

namespace App\Command\AdminBoundaries;

use App\Entity\Admin1Boundary;
use App\Entity\Admin2Boundary;
use App\Entity\Admin3Boundary;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:import:admin-boundaries',
    description: 'Import geographic admin data from files',
)]
class ImportAdminBoundariesDataCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly string $projectDir)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filesystem = new Filesystem();

        try {
            $admin1File = $this->projectDir.'/src/Command/AdminBoundaries/admin1.geojson';
            $admin2File = $this->projectDir.'/src/Command/AdminBoundaries/admin2.geojson';
            $admin3File = $this->projectDir.'/src/Command/AdminBoundaries/admin3.geojson';

            if (!$filesystem->exists($admin1File, $admin2File, $admin3File)) {
                $io->error('Geographic data files not found');

                return Command::FAILURE;
            }

            $this->importAdmin1($admin1File);
            $this->importAdmin2($admin2File);
            $this->importAdmin3($admin3File);

            $io->success('Admin data imported successfully');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Error on admin data import: '.$e->getMessage());

            return Command::FAILURE;
        }
    }

    private function importAdmin1(string $file)
    {
        $geoData = json_decode(file_get_contents($file), true);

        foreach ($geoData['features'] as $feature) {
            $admin1 = new Admin1Boundary();
            $admin1->setAdm1Name($feature['properties']['adm1_name']);
            $admin1->setAdm1Pcode($feature['properties']['adm1_pcode']);
            $admin1->setGeometry(json_encode($feature['geometry']));

            $this->entityManager->persist($admin1);
        }

        $this->entityManager->flush();
    }

    private function importAdmin2(string $file)
    {
        $geoData = json_decode(file_get_contents($file), true);
        foreach ($geoData['features'] as $feature) {
            $admin2 = new Admin2Boundary();
            $admin2->setAdm1Name($feature['properties']['adm1_name']);
            $admin2->setAdm1Pcode($feature['properties']['adm1_pcode']);
            $admin2->setAdm2Name($feature['properties']['adm2_name']);
            $admin2->setAdm2Pcode($feature['properties']['adm2_pcode']);
            $admin2->setGeometry(json_encode($feature['geometry']));

            $this->entityManager->persist($admin2);
        }

        $this->entityManager->flush();
    }

    private function importAdmin3(string $file)
    {
        $geoData = json_decode(file_get_contents($file), true);
        foreach ($geoData['features'] as $feature) {
            $admin3 = new Admin3Boundary();
            $admin3->setAdm1Name($feature['properties']['adm1_name']);
            $admin3->setAdm1Pcode($feature['properties']['adm1_pcode']);
            $admin3->setAdm2Name($feature['properties']['adm2_name']);
            $admin3->setAdm2Pcode($feature['properties']['adm2_pcode']);
            $admin3->setAdm3Name($feature['properties']['adm3_name']);
            $admin3->setAdm3Pcode($feature['properties']['adm3_pcode']);
            $admin3->setGeometry(json_encode($feature['geometry']));

            $this->entityManager->persist($admin3);
        }

        $this->entityManager->flush();
    }
}
