export class QgisMapMaplibreService {
  static qgisServerURL = import.meta.env.VITE_QGIS_SERVER_URL
  // Get the URL of a plain image covering the map for Qgis projects not being visualised as WMS
  static getMapAsImageURL(map: maplibregl.Map, qgisProjectName: string, layers: string[]): string {
    if (!map || !qgisProjectName || !layers) return ''
    const width = map.getCanvas().width
    const height = map.getCanvas().height

    const bounds = map.getBounds()
    const southWest = bounds.getSouthWest()
    const northEast = bounds.getNorthEast()
    const bbox3857 = [southWest.lat, southWest.lng, northEast.lat, northEast.lng]
    return `${this.qgisServerURL}${qgisProjectName}?SERVICE=WMS&VERSION=1.3.0&LAYERS=${layers.join(',')}&REQUEST=GetMap&FORMAT=image/png&TRANSPARENT=true&CRS=EPSG:4326&TILED=false&DPI=72&WIDTH=${width}&HEIGHT=${height}&BBOX=${bbox3857}`
  }

  // Get the coordinates of the map in order to place a map image on the Maplibre map
  static getMapAsImageCoordinates(
    map: maplibregl.Map
  ): [[number, number], [number, number], [number, number], [number, number]] {
    if (!map)
      return [
        [0, 0],
        [0, 0],
        [0, 0],
        [0, 0]
      ]
    return [
      [map.getBounds().getWest(), map.getBounds().getNorth()],
      [map.getBounds().getEast(), map.getBounds().getNorth()],
      [map.getBounds().getEast(), map.getBounds().getSouth()],
      [map.getBounds().getWest(), map.getBounds().getSouth()]
    ]
  }

  // Called when the map moved in order to actualise the coordinates and the content of the map image
  static updateMapImageSourceCoordinates(
    map: maplibregl.Map,
    source: string,
    qgisProjectName: string,
    layers: string[]
  ): void {
    if (!map || !source || !qgisProjectName || !layers) return
    const maplibreSource = map.getSource(source) as maplibregl.ImageSource
    if (!maplibreSource) return
    maplibreSource.updateImage({
      url: this.getMapAsImageURL(map, qgisProjectName, layers),
      coordinates: this.getMapAsImageCoordinates(map)
    })
  }

  // An image source only have one layer as it's a raster source
  static addImageSourceAndLayers(
    map: maplibregl.Map,
    sourceName: string,
    qgisProjectName: string,
    layers: string[],
    firstAdd = true
  ): void {
    if (!map || !sourceName || !qgisProjectName || !layers) return
    if (map.getLayer(sourceName)) map.removeLayer(sourceName)
    if (map.getSource(sourceName)) map.removeSource(sourceName)

    map?.addSource(sourceName, {
      type: 'image',
      url: this.getMapAsImageURL(map, qgisProjectName, layers),
      coordinates: this.getMapAsImageCoordinates(map)
    })

    map?.addLayer({
      id: sourceName,
      type: 'raster',
      source: sourceName,
      paint: {
        'raster-fade-duration': 0
      },
      metadata: { isPersistent: true }
    })

    // Check if the layer has been already added to the map
    // if not the event will be triggered as much time as the layer has been added to the map
    if (firstAdd) {
      map?.on('moveend', () => {
        try {
          QgisMapMaplibreService.updateMapImageSourceCoordinates(
            map as maplibregl.Map,
            sourceName,
            qgisProjectName,
            layers
          )
        } catch (error) {
          console.error('Error updating map image source coordinates', error)
        }
      })
    }
  }

  // A WMS source only have one layer as it's a raster source
  static addWMSSourceAndLayer(
    map: maplibregl.Map,
    sourceName: string,
    qgisProjectName: string,
    layers: string[]
  ): void {
    if (!map || !sourceName) return
    if (map.getLayer(sourceName)) map.removeLayer(sourceName)
    if (map.getSource(sourceName)) map.removeSource(sourceName)

    map?.addSource(sourceName, {
      type: 'raster',
      tiles: [
        `${window.location.origin}${this.qgisServerURL}/${qgisProjectName}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.3.0&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layers.join(',')}`
      ],
      tileSize: 256
    })
    map.addLayer({
      id: sourceName,
      type: 'raster',
      source: sourceName,
      paint: {},
      metadata: { isPersistent: true }
    })
  }

  static removeSourceAndLayers(map: maplibregl.Map, sourceName: string) {
    if (!map || !sourceName) return
    map.removeLayer(sourceName)
    map.removeSource(sourceName)
  }
}
