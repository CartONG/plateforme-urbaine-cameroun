export const nestedObjectsToIri = (payload: any) => {
  for (const key in payload) {
    if (Array.isArray(payload[key]) && payload[key][0]?.['@id']) {
      payload[key] = payload[key].map((x: any) => x['@id'])
    } else if (typeof payload[key] === 'object' && payload[key] && payload[key]['@id']) {
      payload[key] = payload[key]['@id']
    }
  }
  return payload
}
