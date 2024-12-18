import type Layer from '@/models/interfaces/map/Layer'

export default class LayerService {
  static initLayer(layer: Layer): Layer {
    return {
      ...layer,
      isShown: true,
      opacity: 100
    }
  }

  static initSubLayer(data: any[]): Layer[] {
    return data.map((item) => this.initLayer({ id: item.id, name: item.name }))
  }
}
