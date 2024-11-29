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

    public function getOsmPlaceByIdandType(int $osmId, string $osmType): ?GeoData
    {
        $osmPlaceData = $this->fetchOsmPlaceById($osmId, $osmType);
        if (null !== $osmPlaceData) {
            return $this->jsonToGeoData($osmPlaceData);
        }

        return null;
    }

    public function fetchOsmPlaceById(int $osmId, string $osmType): ?array
    {
        $osmIdentifier = strtoupper($osmType)[0].$osmId;
        $url = sprintf('https://'.$this->params->get('domain').'/nominatim/lookup?osm_ids='.$osmIdentifier.'&format=json');

        $response = $this->httpClient->request('GET', $url);
        $data = $response->toArray();

        if (!empty($data[0])) {
            return $data[0];
        }

        return null;
    }

    public function jsonToGeoData(array $data): ?GeoData
    {
        $geoData = new GeoData();
        $geoData
          ->setOsmId($data['osm_id'])
          ->setOsmType($data['osm_type'])
          ->setLatitude($data['lat'])
          ->setLongitude($data['lon'])
          ->setName($data['display_name'])
          ->setAddress($data['address']);

        return $geoData;
    }
}
