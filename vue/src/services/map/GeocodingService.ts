import { nominatimClient } from '@/plugins/axios/nominatim'
import type { NominatimSearchType } from '@/models/enums/geo/NominatimSearchType'
import { OsmPlaceType } from '@/models/enums/geo/OsmPlaceType'
import type { GeocodingItem } from '@/models/interfaces/geo/GeocodingItem'
import type { GeoData } from '@/models/interfaces/geo/GeoData'
import type { OsmData } from '@/models/interfaces/geo/OsmData'

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
            console.log('feature.properties.geocoding', feature)
            const point: OsmData = {
              osmId: feature.properties.geocoding.osm_id,
              osmType: feature.properties.geocoding.osm_type,
              osmName: feature.properties.geocoding.label
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
    return {
      osmId: geoData.osmId,
      osmType: geoData.osmType,
      osmName: geoData.name
    }
  }

  static async getBbox(osmData: OsmData): Promise<OsmData> {
    return await nominatimClient
      .get('/lookup?=', {
        params: {
          osm_ids: this.getOsmIdentifier(osmData),
          format: 'geojson'
        }
      })
      .then((response) => {
        const geojson = response.data
        const feature = geojson.features[0]
        osmData.bbox = feature.bbox
        osmData.coords = feature.geometry.coordinates
        return osmData
      })
  }

  static getOsmIdentifier(osmData: OsmData): string {
    return `${osmData.osmType[0].toUpperCase()}${osmData.osmId}`
  }

  static getLocationName(geoData: GeoData): string {
    if (!geoData) return ''
    return geoData.name.split(', ').splice(0, 2).join(', ')
  }
}
