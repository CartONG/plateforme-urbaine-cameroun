export const uniqueArray = (array: any[], key = 'id') => {
  return array.filter((obj1, i, arr) => 
  arr.findIndex(obj2 => (obj2[key] === obj1[key])) === i
)};

export const reduceText = (text: string, length: number) => {
  if (text.length > length) {
      return text.slice(0, length) + '...';
  }
  return text;
}