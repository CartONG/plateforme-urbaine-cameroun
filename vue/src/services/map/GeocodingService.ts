import { nominatimClient } from '@/plugins/axios/nominatim'
import type { NominatimSearchType } from '@/models/enums/geo/NominatimSearchType'
import { OsmPlaceType } from '@/models/enums/geo/OsmPlaceType'
import type { GeocodingItem } from '@/models/interfaces/geo/GeocodingItem'
import type { GeoData } from '@/models/interfaces/geo/GeoData'

export default class GeocodingService {
  static forwardGeocode = async (
    search: string,
    searchType: NominatimSearchType,
    osmPlaceTypesFilter: OsmPlaceType[] = Object.values(OsmPlaceType)
  ): Promise<GeocodingItem[]> => {
    const places: GeocodingItem[] = []
    // Nominatim documentation : https://nominatim.org/release-docs/latest/api/Search/
    // GeocodeJSON : https://nominatim.org/release-docs/latest/api/Output/#geocodejson
    try {
      await nominatimClient
        .get('/search', {
          params: {
            [searchType]: search,
            format: 'geocodejson',
            polygon_geojson: 0,
            addressdetails: 1,
            keywords: 1
          }
        })
        .then((response) => {
          const geojson = response.data
          for (const feature of geojson.features) {
            const point: GeoData = {
              osmId: feature.properties.geocoding.osm_id.toString(),
              osmType: feature.properties.geocoding.osm_type,
              name: feature.properties.geocoding.label,
              latitude: feature.geometry.coordinates[1],
              longitude: feature.geometry.coordinates[0],
              coords: {
                lat: feature.geometry.coordinates[1],
                lng: feature.geometry.coordinates[0]
              }
            }
            if (osmPlaceTypesFilter.includes(feature.properties.geocoding.type)) {
              places.push(point)
            }
          }
        })
    } catch (error) {
      console.error(`Failed to forwardGeocode with error: ${error}`)
    }
    return places
  }

  static geoDataToGeocodingItem(geoData: GeoData): GeocodingItem | null {
    if (!geoData) return null
    return geoData
  }

  static async getBbox(geoData: GeoData): Promise<GeoData> {
    return await nominatimClient
      .get('/lookup?=', {
        params: {
          osm_ids: this.getOsmIdentifier(geoData),
          format: 'geojson'
        }
      })
      .then((response) => {
        const geojson = response.data
        const feature = geojson.features[0]
        geoData.bbox = feature.bbox
        geoData.latitude = feature.geometry.coordinates[1]
        geoData.longitude = feature.geometry.coordinates[0]
        geoData.coords = {
          lat: feature.geometry.coordinates[1],
          lng: feature.geometry.coordinates[0]
        }
        return geoData
      })
  }

  static getOsmIdentifier(geoData: GeoData): string | null {
    return geoData?.osmType ? `${geoData.osmType[0].toUpperCase()}${geoData.osmId}` : null
  }

  static getLocationName(geoData: GeoData): string {
    if (!geoData) return ''
    return geoData.name
      ? geoData.name.split(', ').splice(0, 2).join(', ')
      : geoData.coords?.lat + ', ' + geoData.coords?.lng
  }
}
