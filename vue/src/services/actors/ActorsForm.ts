import type { Actor } from '@/models/interfaces/Actor'
import { toTypedSchema } from '@vee-validate/zod'
import { useField, useForm } from 'vee-validate'
import { z } from 'zod'
import { i18n } from '@/plugins/i18n'
import { CommonZodSchema } from '../forms/CommonZodSchema'

export class ActorsFormService {
  static getActorsForm(actorToEdit: Actor | null) {
    const zodModels = CommonZodSchema.getDefinitions()

    const actorSchema = z.object({
      ///////// Main infos \\\\\\\\\
      name: z.string().min(1, { message: i18n.t('forms.errorMessages.required') }),
      acronym: z.string().optional(),
      category: z.string().min(1, { message: i18n.t('forms.errorMessages.required') }),
      otherCategory: z.string().optional(),
      expertises: zodModels.symfonyRelations,
      otherExpertise: z.string().optional(),
      thematics: zodModels.symfonyRelations,
      otherThematic: z.string().optional(),
      administrativeScopes: zodModels.symfonyRelations,
      description: zodModels.description,

      ///////// Contact \\\\\\\\\
      officeName: z.string().optional(),
      officeAddress: z.string().optional(),
      geoData: zodModels.geoData,
      contactName: z.string().optional(),
      contactPosition: z.string().optional(),
      website: zodModels.website,
      email: zodModels.email,
      phone: zodModels.phone
    })
    const { errors, handleSubmit, isSubmitting } = useForm<Actor>({
      initialValues: actorToEdit,
      validationSchema: toTypedSchema(actorSchema)
    })
    const form = {
      name: useField('name', '', { validateOnValueUpdate: false }),
      acronym: useField('acronym', '', { validateOnValueUpdate: false }),
      category: useField('category', '', { validateOnValueUpdate: false }),
      otherCategory: useField('otherCategory', '', { validateOnValueUpdate: false }),
      expertises: useField('expertises', '', { validateOnValueUpdate: false }),
      otherExpertise: useField('otherExpertise', '', { validateOnValueUpdate: false }),
      thematics: useField('thematics', '', { validateOnValueUpdate: false }),
      otherThematic: useField('otherThematic', '', { validateOnValueUpdate: false }),
      administrativeScopes: useField('administrativeScopes', '', { validateOnValueUpdate: false }),
      description: useField('description', '', { validateOnValueUpdate: false }),
      officeName: useField('officeName', '', { validateOnValueUpdate: false }),
      officeAddress: useField('officeAddress', '', { validateOnValueUpdate: false }),
      geoData: useField('geoData', '', { validateOnValueUpdate: false }),
      contactName: useField('contactName', '', { validateOnValueUpdate: false }),
      contactPosition: useField('contactPosition', '', { validateOnValueUpdate: false }),
      website: useField('website', '', { validateOnValueUpdate: false }),
      email: useField('email', '', { validateOnValueUpdate: false }),
      phone: useField('phone', '', { validateOnValueUpdate: false })
    }
    return { form, errors, handleSubmit, isSubmitting }
  }
}
