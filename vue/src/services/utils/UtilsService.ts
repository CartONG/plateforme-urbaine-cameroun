import { i18n } from '@/plugins/i18n'

export const uniqueArray = (array: any[], key = 'id') => {
  return array.filter((obj1, i, arr) => arr.findIndex((obj2) => obj2[key] === obj1[key]) === i)
}

export const reduceText = (text: string, length: number) => {
  if (text.length > length && text != null) {
    return text.slice(0, length) + '...'
  }
  return text
}

export const debounce = (func: any, wait = 500) => {
  let timeoutId: ReturnType<typeof setTimeout>
  return (...args: any[]) => {
    clearTimeout(timeoutId)
    timeoutId = setTimeout(() => {
      func(...args)
    }, wait)
  }
}

export function getNestedObjectValue(obj: any, propStr = '') {
  const keys = propStr.split('.')
  return keys.reduce((acc, key) => acc[key], obj)
}

export function localizeDate(
  date: string | Date,
  format: Intl.DateTimeFormatOptions = { year: 'numeric', month: 'long', day: 'numeric' }
) {
  date = new Date(date)
  return date.toLocaleDateString('fr-FR', format)
}

export function localizeTime(
  date: string | Date,
  format: Intl.DateTimeFormatOptions = { hour: 'numeric', minute: 'numeric' }
) {
  date = new Date(date)
  return date.toLocaleTimeString('fr-FR', format)
}

export function getDateRangeLabel(startAt: string | Date | null, endAt: string | Date | null) {
  if (!startAt) return ''
  startAt = new Date(startAt)
  startAt.setHours(0, 0, 0, 0)

  if (!endAt || isSameDay(startAt, endAt))
    return localizeDate(startAt, { year: 'numeric', month: 'long', day: 'numeric' })

  endAt = new Date(endAt)
  endAt.setHours(0, 0, 0, 0)

  const showStartAtMonth = startAt.getMonth() !== endAt.getMonth()
  const showStartAtYear = startAt.getFullYear() !== endAt.getFullYear()
  const localizedStartAt = localizeDate(startAt, {
    year: showStartAtYear ? 'numeric' : undefined,
    month: showStartAtMonth ? 'long' : undefined,
    day: 'numeric'
  })
  const localizedEndAt = localizeDate(endAt, { year: 'numeric', month: 'long', day: 'numeric' })
  return i18n.t('date.dateRangeLabel', { startAt: localizedStartAt, endAt: localizedEndAt })
}

export function getDateDiff(date1: string | Date, date2: string | Date): number {
  date1 = new Date(date1)
  date1.setHours(0, 0, 0, 0)
  date2 = new Date(date2)
  date2.setHours(0, 0, 0, 0)
  return date1.getTime() - date2.getTime()
}

export function isSameDay(date1: string | Date, date2: string | Date) {
  return getDateDiff(date1, date2) === 0
}
