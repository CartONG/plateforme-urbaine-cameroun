/**
 * Génère les créneaux horaires possibles avec un pas de 30 minutes,
 * bornés entre 08:30 et 17:30 (horaires d'ouverture du tiers-lieu).
 */
export function getHalfHourTimeOptions(): string[] {
  const options: string[] = []
  const minutes = ['00', '30']

  for (let h = 8; h <= 17; h++) {
    const hourStr = h.toString().padStart(2, '0')
    for (const m of minutes) {
      if (h === 8 && m === '00') continue

      options.push(`${hourStr}:${m}`)
    }
  }
  return options
}