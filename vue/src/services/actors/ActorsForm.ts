import type { Actor } from '@/models/interfaces/Actor'
import { toTypedSchema } from '@vee-validate/zod'
import { useField, useForm } from 'vee-validate'
import { z } from 'zod'
import { i18n } from '@/plugins/i18n'
import { CommonZodSchema } from '../forms/CommonZodSchema'
import { AdministrativeScope } from '@/models/enums/AdministrativeScope'

export class ActorsFormService {
  static getActorsForm(actorToEdit: Actor | null) {
    const zodModels = CommonZodSchema.getDefinitions()

    const actorSchema = z.object({
      ///////// Main infos \\\\\\\\\
      name: z.string().min(1, { message: i18n.t('forms.errorMessages.required') }),
      acronym: z.string().optional(),
      category: z.string().min(1, { message: i18n.t('forms.errorMessages.required') }),
      expertises: zodModels.symfonyRelations,
      thematics: zodModels.symfonyRelations,
      administrativeScopes: z.array(z.nativeEnum(AdministrativeScope)),
      admin1List: zodModels.admin1Boundaries.optional(),
      admin2List: zodModels.admin2Boundaries.optional(),
      admin3List: zodModels.admin3Boundaries.optional(),
      description: zodModels.description,

      ///////// Contact \\\\\\\\\
      officeName: z.string().optional(),
      officeAddress: z.string().optional(),
      officeLocation: zodModels.latLngString.optional(),
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
      expertises: useField('expertises', '', { validateOnValueUpdate: false }),
      thematics: useField('thematics', '', { validateOnValueUpdate: false }),
      administrativeScopes: useField('administrativeScopes', '', { validateOnValueUpdate: false }),
      admin1List: useField('admin1List', '', { validateOnValueUpdate: false }),
      admin2List: useField('admin2List', '', { validateOnValueUpdate: false }),
      admin3List: useField('admin3List', '', { validateOnValueUpdate: false }),
      description: useField('description', '', { validateOnValueUpdate: false }),
      officeName: useField('officeName', '', { validateOnValueUpdate: false }),
      officeAddress: useField('officeAddress', '', { validateOnValueUpdate: false }),
      officeLocation: useField('officeLocation', '', { validateOnValueUpdate: false }),
      contactName: useField('contactName', '', { validateOnValueUpdate: false }),
      contactPosition: useField('contactPosition', '', { validateOnValueUpdate: false }),
      website: useField('website', '', { validateOnValueUpdate: false }),
      email: useField('email', '', { validateOnValueUpdate: false }),
      phone: useField('phone', '', { validateOnValueUpdate: false })
    }
    return { form, errors, handleSubmit, isSubmitting }
  }
}
