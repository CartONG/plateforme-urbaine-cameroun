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
