import FileUploader from '@/services/files/FileUploader'

export const onInvalidSubmit = () => {
  const el = document.querySelector(`.v-input--error`)
  if (el) {
    el.scrollIntoView({
      behavior: 'smooth',
      block: 'center',
      inline: 'nearest'
    })
  }
}

/**
 * Upload all files in the object and replace them with the media object iri
 */
export const handleFileUpload = async (object: any) => {
  const uploadPromises = Object.keys(object).map(async (fieldName) => {
    if (object[fieldName] instanceof File) {
      const response = await FileUploader.uploadFile(object[fieldName])
      object[fieldName] = response['@id']
    }
  })
  await Promise.all(uploadPromises)
  return object
}