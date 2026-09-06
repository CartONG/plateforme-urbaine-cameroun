import { i18n } from '@/plugins/i18n'
import { toTypedSchema } from '@vee-validate/zod'
import { useField, useForm } from 'vee-validate'
import { z } from 'zod'
import { CommonZodSchema } from '@/services/forms/CommonZodSchema'

export class BookingFormService {
  static getBookingForm(defaults: Record<string, any> = {}, isEditMode = false) {
    const zodModels = CommonZodSchema.getDefinitions()

    const bookingSchema = z.object({
      // Étape 1 — Identification du demandeur
      lastName: z.string({ required_error: i18n.t('forms.errorMessages.required') }).min(1),
      firstName: z.string({ required_error: i18n.t('forms.errorMessages.required') }).min(1),
      organization: z.string().optional(),
      role: z.string({ required_error: i18n.t('forms.errorMessages.required') }).min(1),
      email: zodModels.email,
      phone: z.string({ required_error: i18n.t('forms.errorMessages.required') }).min(1),

      // Étape 2 — Informations sur l'évènement / activité
      title: z.string({ required_error: i18n.t('forms.errorMessages.required') }).min(1),
      eventActivityType: z.string().optional().nullable(),
      // En édition, l'utilisateur n'est pas obligé de re-téléverser ses pièces
      // jointes existantes : ces champs deviennent optionnels, seule une
      // nouvelle sélection de fichier les remplace.
      agenda: isEditMode
        ? z.instanceof(File).optional().nullable()
        : z.instanceof(File, { message: i18n.t('forms.errorMessages.required') }),
      resourceDocument: isEditMode
        ? z.array(z.instanceof(File)).optional().nullable()
        : z.array(z.instanceof(File)).min(1, { message: i18n.t('forms.errorMessages.required') }),
      otherDocument: z.array(z.instanceof(File)).optional().nullable(),
      additionalInformation: z.string().optional(),
      participantCount: z
        .number({ required_error: i18n.t('forms.errorMessages.required') })
        .positive({ message: i18n.t('forms.errorMessages.positive') }),

      // Étape 3 — Créneau souhaité
      date: z.string({ required_error: i18n.t('forms.errorMessages.required') }),
      startTime: z.string({ required_error: i18n.t('forms.errorMessages.required') }),
      endTime: z.string({ required_error: i18n.t('forms.errorMessages.required') })
    })

    const { errors, handleSubmit, isSubmitting, values, setValues } = useForm({
      initialValues: defaults,
      validationSchema: toTypedSchema(bookingSchema)
    })

    const form = {
      lastName: useField<string>('lastName', '', { validateOnValueUpdate: false }),
      firstName: useField<string>('firstName', '', { validateOnValueUpdate: false }),
      organization: useField<string>('organization', '', { validateOnValueUpdate: false }),
      role: useField<string>('role', '', { validateOnValueUpdate: false }),
      email: useField<string>('email', '', { validateOnValueUpdate: false }),
      phone: useField<string>('phone', '', { validateOnValueUpdate: false }),
      title: useField<string>('title', '', { validateOnValueUpdate: false }),
      eventActivityType: useField<string | null>('eventActivityType', '', { validateOnValueUpdate: false }),
      agenda: useField<File | null>('agenda', '', { validateOnValueUpdate: false }),
      resourceDocument: useField<File[] | null>('resourceDocument', '', {
        validateOnValueUpdate: false
      }),
      otherDocument: useField<File[] | null>('otherDocument', '', {
        validateOnValueUpdate: false
      }),
      additionalInformation: useField<string>('additionalInformation', '', {
        validateOnValueUpdate: false
      }),
      participantCount: useField<number>('participantCount', '', { validateOnValueUpdate: false }),
      date: useField<string>('date', '', { validateOnValueUpdate: false }),
      startTime: useField<string>('startTime', '', { validateOnValueUpdate: false }),
      endTime: useField<string>('endTime', '', { validateOnValueUpdate: false })
    }

    const stepFields: Record<number, (keyof typeof form)[]> = {
      1: ['lastName', 'firstName', 'organization', 'role', 'email', 'phone'],
      2: [
        'title',
        'eventActivityType',
        'agenda',
        'resourceDocument',
        'otherDocument',
        'additionalInformation',
        'participantCount'
      ],
      3: ['date', 'startTime', 'endTime']
    }

    return { form, errors, handleSubmit, isSubmitting, values, setValues, stepFields }
  }

  static getInformationSourceForm() {
    const schema = z.object({
      informationSource: z.string({ required_error: i18n.t('forms.errorMessages.required') })
    })

    const { handleSubmit, isSubmitting } = useForm({
      validationSchema: toTypedSchema(schema)
    })

    const form = {
      informationSource: useField('informationSource', '', { validateOnValueUpdate: false })
    }

    return { form, handleSubmit, isSubmitting }
  }
}