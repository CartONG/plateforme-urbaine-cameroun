import { i18n } from '@/plugins/i18n'
import { toTypedSchema } from '@vee-validate/zod'
import { useField, useForm } from 'vee-validate'
import { z } from 'zod'
import { CommonZodSchema } from '@/services/forms/CommonZodSchema'
import type { Space } from '@/models/interfaces/divercity/Space'

export class SpacesFormService {
  static getSpaceForm(spaceToEdit: Space | null) {
    const zodModels = CommonZodSchema.getDefinitions()

    const spaceSchema = z.object({
      name: z
        .string({ required_error: i18n.t('forms.errorMessages.required') })
        .min(3, { message: i18n.t('forms.errorMessages.minlength', { min: 3 }) }),
      description: zodModels.descriptionRequired,
      maxCapacity: z
        .number({ required_error: i18n.t('forms.errorMessages.required') })
        .positive({ message: i18n.t('forms.errorMessages.positive') }),
      contact: z.string().optional(),
      email: zodModels.email,
      videoLink: zodModels.website,
      equipment: z.string().optional()
    })

    const { errors, handleSubmit, isSubmitting } = useForm<Space>({
      initialValues: spaceToEdit ?? undefined,
      validationSchema: toTypedSchema(spaceSchema)
    })

    const form = {
      name: useField('name', '', { validateOnValueUpdate: false }),
      description: useField('description', '', { validateOnValueUpdate: false }),
      maxCapacity: useField('maxCapacity', '', { validateOnValueUpdate: false }),
      contact: useField('contact', '', { validateOnValueUpdate: false }),
      email: useField('email', '', { validateOnValueUpdate: false }),
      videoLink: useField('videoLink', '', { validateOnValueUpdate: false }),
      equipment: useField('equipment', '', { validateOnValueUpdate: false })
    }

    return { form, errors, handleSubmit, isSubmitting }
  }

  static getHighlightForm() {
    const zodModels = CommonZodSchema.getDefinitions()

    const highlightSchema = z.object({
      year: z
        .number({ required_error: i18n.t('forms.errorMessages.required') })
        .min(2000)
        .max(2100),
      report: zodModels.file.optional()
    })

    const { errors, handleSubmit, isSubmitting } = useForm({
      validationSchema: toTypedSchema(highlightSchema)
    })

    const form = {
      year: useField('year', '', { validateOnValueUpdate: false }),
      report: useField('report', '', { validateOnValueUpdate: false })
    }

    return { form, errors, handleSubmit, isSubmitting }
  }
}