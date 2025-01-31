import { toTypedSchema } from '@vee-validate/zod'
import { useField, useForm } from 'vee-validate'
import { z } from 'zod'
import { i18n } from '@/plugins/i18n'
import { CommonZodSchema } from '@/services/forms/CommonZodSchema'
import type { Atlas } from '@/models/interfaces/Atlas'

export class AtlasFormService {
  static getForm(atlas: Atlas | null) {
    const zodModels = CommonZodSchema.getDefinitions()
    // @ts-ignore
    const atlasSchema: z.ZodType<Partial<Atlas>> = z.object({
      name: z.string().min(1, { message: i18n.t('forms.errorMessages.required') }),
      atlasGroup: z.string().min(1, { message: i18n.t('forms.errorMessages.required') }),
      maps: zodModels.symfonyRelations
    })

    const { errors, handleSubmit, isSubmitting, setFieldValue } = useForm<Partial<Atlas>>({
      initialValues: atlas,
      validationSchema: toTypedSchema(atlasSchema)
    })

    const form = {
      name: useField('name'),
      atlasGroup: useField('atlasGroup'),
      maps: useField('maps')
    }

    return { form, errors, handleSubmit, isSubmitting, setFieldValue }
  }
}
