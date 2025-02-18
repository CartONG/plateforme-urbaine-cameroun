import { apiClient } from '@/plugins/axios/api'
import type { FileObject } from '@/models/interfaces/object/FileObject'
import type { MediaObject } from '@/models/interfaces/object/MediaObject'

export default class FileUploader {
  private static _fileUri: string = '/api/file_objects'
  private static _mediaUri: string = '/api/media_objects'

  public static async uploadFile(file: File): Promise<FileObject> {
    return this.upload(file, this._fileUri)
  }

  public static async uploadMedia(file: File): Promise<MediaObject> {
    return this.upload(file, this._mediaUri)
  }

  private static async upload(file: File, url: string): Promise<FileObject | MediaObject> {
    const form = new FormData()
    form.append('file', file)

    return (
      await apiClient.post(url, form, {
        headers: {
          'Content-Type': 'multipart/form-data',
          accept: 'application/ld+json'
        }
      })
    ).data
  }
}
