import { i18n } from '@/plugins/i18n'
import type { ContentImageFromUserFile } from '@/models/interfaces/ContentImage'

export class InputImageValidator {
  private static readonly MAX_SIZE = 1024 * 1024
  private static readonly ALLOWED_TYPES = [
    'image/png',
    'image/jpeg',
    'image/jpg',
    'image/gif',
    'image/webp',
    'image/bmp'
  ]
  private static message = i18n.t('inputs.images.generic')

  static validateImageFromFile(
    file: File,
    existingFileList: ContentImageFromUserFile[] = []
  ): { isValid: boolean; message: string } {
    if (!file) {
      return { isValid: false, message: this.message + i18n.t('inputs.fileUncorrect') }
    }
    if (!this.ALLOWED_TYPES.includes(file.type)) {
      return { isValid: false, message: this.message + i18n.t('inputs.images.wrongFormat') }
    }
    if (file.size > this.MAX_SIZE) {
      return { isValid: false, message: this.message + i18n.t('inputs.images.wrongSize') }
    }
    if (existingFileList.length > 0) {
      if (existingFileList.some((existingFile) => existingFile.name === file.name)) {
        return { isValid: false, message: this.message + i18n.t('inputs.images.duplicate') }
      }
    }
    return { isValid: true, message: '' }
  }
}
