import { z } from 'zod'
import { i18n } from '@/plugins/i18n'
import { toTypedSchema } from '@vee-validate/zod'
import { useField, useForm } from 'vee-validate'
import type { AppComment } from '@/models/interfaces/Comment'

export class CommentsFormService {
  static getCommentsForm() {
    const commentSchema = z.object({
      message: z.string().min(1, { message: i18n.t('forms.errorMessages.required') }),
      lng: z.string().optional(),
      lat: z.string().optional()
    })

    const { errors, handleSubmit, isSubmitting } = useForm<AppComment>({
      validationSchema: toTypedSchema(commentSchema)
    })

    const form = {
      message: useField('message', '', { validateOnValueUpdate: false }),
      lng: useField('lng', '', { validateOnValueUpdate: false }),
      lat: useField('lat', '', { validateOnValueUpdate: false })
    }

    return { form, errors, handleSubmit, isSubmitting }
  }
}
