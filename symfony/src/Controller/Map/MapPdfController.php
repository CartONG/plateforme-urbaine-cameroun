<?php

namespace App\Controller\Map;

use App\Services\Service\PdfGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapPdfController extends AbstractController
{
    #[Route('/api/export-map', name: 'export_map', methods: ['POST'])]
    public function exportMap(Request $request, PdfGenerator $pdfGenerator): Response
    {
        $data = json_decode($request->getContent(), true);
        $title = $data['title'] ?? null;
        $description = $data['description'] ?? null;
        $mapImage = $data['mapImage'] ?? null;
        $legendList = $data['legendList'] ?? null;
        $logo = $data['logo'] ?? null;
        $footer = $data['footer'] ?? null;
        $scaleDenominator = $data['scaleDenominator'] ?? null;

        if (!$mapImage) {
            return new Response('No image provided', 400);
        }

        $pdfContent = $pdfGenerator->generate('pdf/map.html.twig', [
            'title' => $title,
            'description' => $description,
            'mapUrl' => $mapImage,
            'legendList' => $legendList,
            'logo' => $logo,
            'footer' => $footer,
            'scaleDenominator' => $scaleDenominator,
        ]);

        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="map.pdf"',
        ]);
    }
}
