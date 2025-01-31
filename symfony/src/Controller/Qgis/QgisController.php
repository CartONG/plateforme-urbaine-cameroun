<?php

namespace App\Controller\Qgis;

use GuzzleHttp\Psr7\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class QgisController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private Security $security,
        private ParameterBagInterface $params,
    ) {
    }

    #[Route('/api/qgis/{path}', requirements: ['path' => '.*'], methods: ['GET'], name: 'qgis')]
    public function proxy(
        string $path,
        RequestStack $requestStack,
    ): Response {
        // Add any user permission checks -- TODO
        // $this->denyAccessUnlessGranted('ROLE_USER');

        $queryParams = $requestStack->getCurrentRequest()->query->all();
        $qgisUrl = 'https://qgis.'.$this->params->get('domain').'/'.$path;

        try {
            // Forward the request to QGIS Server
            $qgisResponse = $this->httpClient->request('GET', $qgisUrl, [
                'query' => $queryParams,
            ]);

            // Return the QGIS response
            return new Response(
                $qgisResponse->getContent(),
                $qgisResponse->getStatusCode(),
                $qgisResponse->getHeaders(false)
            );
        } catch (\Exception $e) {
            return $this->json(['error' => 'Failed to fetch data from QGIS Server', 'details' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
