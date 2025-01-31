<?php

namespace App\Services\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DomCrawler\Crawler;

class QgisProjectProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        #[Autowire('%kernel.project_dir%/public/qgis')]
        private string $qgisProjectUploadDirectory,
    ) {
    }

    /**
     * @param QgisProject $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $projectName = explode('.', $data->file->getClientOriginalName())[0];
        $qgisProjectDirectory = $this->qgisProjectUploadDirectory.'/'.$projectName;

        $this->extractZip($data->file->getRealPath(), $this->qgisProjectUploadDirectory);
        $this->removeUnauthorizedFileType($qgisProjectDirectory);

        if ($this->isValidQgisProject($qgisProjectDirectory, $projectName)) {
            $layerNames = $this->parseLayers($qgisProjectDirectory, $projectName);
            $data->setName($projectName);
            $data->setLayers($layerNames);
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }

    private function isAllowedQgisProjectFile(\SplFileInfo $file): bool
    {
        // Complete here the list of allowed qgis project extensions
        $allowedExtensions = ['qgs', 'dbf', 'shx', 'prj', 'shp', 'cpg', 'dbf', 'sqlite', 'geojson'];

        $fileExtension = $file->getExtension();

        return in_array(strtolower($fileExtension), $allowedExtensions);
    }

    private function removeUnauthorizedFileType(string $folderPath): void
    {
        $files = new \RecursiveDirectoryIterator($folderPath);
        $iterator = new \RecursiveIteratorIterator($files, \RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                if (!$this->isAllowedQgisProjectFile($file)) {
                    unlink($file); // Delete the file
                }
            }
        }
    }

    private function extractZip(string $zipFilePath, string $destinationPath): void
    {
        $zip = new \ZipArchive();
        $zip->open($zipFilePath);
        $zip->extractTo($destinationPath);
        $zip->close();
    }

    private function isValidQgisProject(string $qgisProjectDirectory, string $qgisProjectName): bool
    {
        return $this->hasQgisFileInRoot($qgisProjectDirectory, $qgisProjectName);
    }

    private function hasQgisFileInRoot(string $qgisProjectDirectory, string $qgisProjectName): bool
    {
        $qgisProjectFile = $qgisProjectName.'.qgs';
        $files = scandir($qgisProjectDirectory);
        foreach ($files as $file) {
            if ($file === $qgisProjectFile) {
                return true;
            }
        }

        return false;
    }

    private function parseLayers(string $qgisProjectDirectory, string $qgisProjectName): ?array
    {
        $xml = file_get_contents($qgisProjectDirectory.'/'.$qgisProjectName.'.qgs');
        $crawler = new Crawler($xml);
        $layerNames = $crawler
            ->filter('projectlayers > maplayer')
            ->each(function (Crawler $node) {
                return $node->filter('layername')->text();
            });

        return $layerNames;
    }
}
