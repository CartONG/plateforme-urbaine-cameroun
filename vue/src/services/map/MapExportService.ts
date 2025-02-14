import { z } from 'zod'
import { i18n } from '@/plugins/i18n'
import { toTypedSchema } from '@vee-validate/zod'
import { useForm, useField } from 'vee-validate'

export class MapExportService {
  static getExportForm() {
    const formSchema = z.object({
      title: z.string().min(1, { message: i18n.t('forms.errorMessages.required') }),
      description: z.string().optional()
    })
    const { errors, handleSubmit, isSubmitting } = useForm({
      validationSchema: toTypedSchema(formSchema)
    })
    const form = {
      title: useField('title', '', { validateOnValueUpdate: false }),
      description: useField('description', '', { validateOnValueUpdate: false })
    }
    return { form, errors, handleSubmit, isSubmitting }
  }
}
