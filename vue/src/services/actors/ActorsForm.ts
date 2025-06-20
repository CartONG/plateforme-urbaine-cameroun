import { AdministrativeScope } from '@/models/enums/AdministrativeScope'
import type { Actor } from '@/models/interfaces/Actor'
import { i18n } from '@/plugins/i18n'
import { toTypedSchema } from '@vee-validate/zod'
import { useField, useForm } from 'vee-validate'
import { z } from 'zod'
import { CommonZodSchema } from '../forms/CommonZodSchema'

export class ActorsFormService {
  static getActorsForm(actorToEdit: Actor | null) {
    const zodModels = CommonZodSchema.getDefinitions()

    const actorSchema = z.object({
      ///////// Main infos \\\\\\\\\
      name: z
        .string({ required_error: i18n.t('forms.errorMessages.required') })
        .min(3, { message: i18n.t('forms.errorMessages.minlength', { min: 3 }) }),
      acronym: z.string().optional(),
      description: zodModels.descriptionRequired,
      category: z.string({ required_error: i18n.t('forms.errorMessages.required') }),
      otherCategory: z.string().optional(),
      thematics: zodModels.thematics,
      otherThematic: z.string().optional(),
      odds: zodModels.odds,
      administrativeScopes: z.array(z.nativeEnum(AdministrativeScope), {
        required_error: i18n.t('forms.errorMessages.required')
      }),
      admin1List: zodModels.admin1Boundaries.optional(),
      admin3List: zodModels.admin3Boundaries.optional(),

      ///////// Contact \\\\\\\\\
      officeName: z.string().optional(),
      officeAddress: z.string().optional(),
      geoData: zodModels.geoDataNullable.optional(),
      contactName: z.string().optional(),
      contactPosition: z.string().optional(),
      website: zodModels.website,
      email: zodModels.email,
      phone: zodModels.phone,
      banoc: zodModels.banoc,
      banocUrl: zodModels.banocUrl
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
      thematics: useField('thematics', '', { validateOnValueUpdate: false }),
      otherThematic: useField('otherThematic', '', { validateOnValueUpdate: false }),
      odds: useField('odds', '', { validateOnValueUpdate: false }),
      administrativeScopes: useField('administrativeScopes', '', { validateOnValueUpdate: false }),
      admin1List: useField('admin1List', '', { validateOnValueUpdate: false }),
      admin3List: useField('admin3List', '', { validateOnValueUpdate: false }),
      description: useField('description', '', { validateOnValueUpdate: false }),
      officeName: useField('officeName', '', { validateOnValueUpdate: false }),
      officeAddress: useField('officeAddress', '', { validateOnValueUpdate: false }),
      geoData: useField('geoData', '', { validateOnValueUpdate: false }),
      contactName: useField('contactName', '', { validateOnValueUpdate: false }),
      contactPosition: useField('contactPosition', '', { validateOnValueUpdate: false }),
      website: useField('website', '', { validateOnValueUpdate: false }),
      email: useField('email', '', { validateOnValueUpdate: false }),
      phone: useField('phone', '', { validateOnValueUpdate: false }),
      banoc: useField('banoc', '', { validateOnValueUpdate: false }),
      banocUrl: useField('banocUrl', '', { validateOnValueUpdate: false })
    }
    return { form, errors, handleSubmit, isSubmitting }
  }
}
