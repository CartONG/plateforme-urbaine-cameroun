<?php

namespace App\Services\Service;

use App\Entity\GeoData;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NominatimService
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private ParameterBagInterface $params,
    ) {
    }

    public function fetchOsmPlace(GeoData $geoData): ?GeoData
    {
        $osmPlaceData = $this->fetchOsmPlaceByOsmIdentifier($geoData->getOsmIdentifier());
        if (null !== $osmPlaceData) {
            return $this->jsonToGeoData($osmPlaceData);
        }

        return null;
    }

    public function fetchOsmPlaceByOsmIdentifier(string $osmIdentifier): ?array
    {
        try {
            $url = sprintf('https://'.$this->params->get('domain').'/nominatim/lookup?osm_ids='.$osmIdentifier.'&format=json');
            $response = $this->httpClient->request('GET', $url);
            $data = $response->toArray();
        } catch (\Exception $e) {
            // use public Nominatim service if issue with local one
            $url = sprintf('https://nominatim.openstreetmap.org/lookup?osm_ids='.$osmIdentifier.'&format=json');
            $response = $this->httpClient->request('GET', $url);
            $data = $response->toArray();
        }

        if (!empty($data[0])) {
            return $data[0];
        }

        return null;
    }

    public function fetchOsmPlaceByCoords(float $latitude, float $longitude): ?array
    {
        $url = sprintf('https://'.$this->params->get('domain').'/nominatim/reverse?format=json&lat=%f&lon=%f', $latitude, $longitude);
        $response = $this->httpClient->request('GET', $url);
        $data = $response->toArray();

        return $data;
    }

    public function jsonToGeoData(array $data): ?GeoData
    {
        $geoData = new GeoData();
        $geoData
          ->setOsmId((string) $data['osm_id'])
          ->setOsmType($data['osm_type'])
          ->setLatitude($data['lat'])
          ->setLongitude($data['lon'])
          ->setName($data['display_name'])
          ->setAddress($data['address']);

        return $geoData;
    }
}
